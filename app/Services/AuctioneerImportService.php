<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : AuctioneerImportService.php
 **/

namespace App\Services;

use App\Models\DealerCategories;
use App\Models\DealerFeaturedImageImport;
use App\Models\DealerImageImport;
use App\Models\Dealers;
use App\Models\DealersFeatures;
use App\Models\DealersMedia;
use Illuminate\Support\Facades\Storage;
use Quantum\base\Models\Countries;
use Quantum\base\Models\Categories;

class AuctioneerImportService
{
    protected $xmlContents;

    protected $imported = 0;

    protected $posts=[];

    public function importFromFile()
    {
        if(!$this->processImportFile()) return false;
        $this->cacheClear();
        return $this->imported;
    }

    public function updateFromFile()
    {
        if(!$this->processUpdateFile()) return false;
        $this->cacheClear();
        return $this->imported;
    }

    public function importFeaturedImages()
    {
        if(!$this->processImageImportFile()) return false;
        //$this->cacheClear();
        return $this->imported;
    }

    public function updateFromFileImageDealers()
    {
        if(!$this->processImageImportDealers()) return false;
        //$this->cacheClear();
        return $this->imported;
    }

    private function processImportFile()
    {
        $exists = Storage::exists('auctioneerImport.xml');
        if(!$exists) return false;
        $this->xmlContents = Storage::get('auctioneerImport.xml');
        $xml = simplexml_load_string($this->xmlContents);
        $namespaces = $xml->getDocNamespaces();

        $uk = Countries::where('name', 'United Kingdom')->firstOrfail();

        foreach ( $xml->channel->item as $item ) {

            $post = [];
            $post['post_title'] = (string) $item->title;
            $post['guid'] = (string) $item->guid;

            $dc = $item->children( 'http://purl.org/dc/elements/1.1/' );
            $post['post_author'] = (string) $dc->creator;
            $content = $item->children( 'http://purl.org/rss/1.0/modules/content/' );
            $excerpt = $item->children( $namespaces['excerpt'] );
            $post['post_content'] = (string) $content->encoded;
            $post['post_excerpt'] = (string) $excerpt->encoded;
            $wp = $item->children( $namespaces['wp'] );
            $post['post_id'] = (int) $wp->post_id;
            $post['post_date'] = (string) $wp->post_date;
            $post['post_date_gmt'] = (string) $wp->post_date_gmt;
            $post['comment_status'] = (string) $wp->comment_status;
            $post['ping_status'] = (string) $wp->ping_status;
            $post['post_name'] = (string) $wp->post_name;
            $post['status'] = (string) $wp->status;
            $post['post_parent'] = (int) $wp->post_parent;
            $post['menu_order'] = (int) $wp->menu_order;
            $post['post_type'] = (string) $wp->post_type;
            $post['post_password'] = (string) $wp->post_password;
            $post['is_sticky'] = (int) $wp->is_sticky;

            foreach ( $item->category as $c ) {
                $att = $c->attributes();
                if ( isset( $att['nicename'] ) )
                    $post['terms'][] = array(
                        'name' => (string) $c,
                        'slug' => (string) $att['nicename'],
                        'domain' => (string) $att['domain']
                    );
            }

            foreach ( $wp->postmeta as $meta ) {
                $post['postmeta'][] = array(
                    'key' => (string) $meta->meta_key,
                    'value' => (string) $meta->meta_value
                );
            }
            if(isset($post['terms'])) $post['terms'] = collect($post['terms']);
            $this->saveImport($post, $uk);
        }
        return true;
    }

    private function saveImport($post, $uk)
    {
        if(!$post['post_title'] || $post['post_title'] == '') return;
        $post['post_title'] = rtrim($post['post_title'], '*');
        $existingDealer = Dealers::where('slug', str_slug($post['post_title']))->first();
        if($existingDealer) return;

        $options = [];
        foreach ($post['postmeta'] as  $postmeta)
        {
            if($postmeta['key'] == 'lp_listingpro_options')
            {
                $options = unserialize($postmeta['value']);
            }
        }

        $location = null;
        if(isset($post['terms']) && count($post['terms']) > 0)
        {
            foreach ($post['terms'] as $term)
            {
                if($term['domain'] != 'location') continue;
                $location = $term['name'];
            }
        }

        $phone = null;
        $email = null;
        $website = null;
        $longitude = null;
        $latitude = null;
        if($options['phone'] != '' && $options['phone'] != 'na') $phone = $options['phone'];
        if($options['email'] != '' && $options['email'] != 'na') $email = $options['email'];
        if($options['website'] != '' && $options['website'] != 'na') $website = $options['website'];
        if($options['longitude'] != '' && $options['longitude'] != 'na' && $options['longitude'] != 0) $longitude = $options['longitude'];
        if($options['latitude'] != '' && $options['latitude'] != 'na' && $options['latitude'] != 0) $latitude = $options['latitude'];


        $dealer = Dealers::create([
            'name' => $post['post_title'],
            'slug' => str_slug($post['post_title']),
            'logo' => '',
            'address' => $options['tagline_text'],
            'phone' => $phone,
            'email' => $email,
            'website' => $website,
            'auction_url',
            'online_bidding_url',
            'details' => $post['post_content'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'type' => 'auctioneer',
            'status' => 'active',
            'county' => $location,
            'country_id' => $uk->id,
            'has_streetview' => 0
        ]);

        $importCategory = DealerCategories::where('slug', 'imported')->firstOrCreate([
            'name' => 'Imported',
            'slug' => 'imported',
            'parent_id' => null,
            'user_id' => null,
        ]);

        $dealerCats = [];
        $dealerfeatures = [];
        if(isset($post['terms']) && count($post['terms']) > 0)
        {
            foreach ($post['terms'] as $term)
            {
                if($term['domain'] == 'listing-category')
                {
                    $category = DealerCategories::firstOrCreate([
                        'name' => $term['name'],
                        'slug' => $term['slug'],
                        'parent_id' => $importCategory->id,
                        'area' => $importCategory->slug,
                        'user_id' => null,
                    ]);
                    array_push($dealerCats, $category->id);
                }

                if($term['domain'] == 'features')
                {
                    $feature = DealersFeatures::firstOrCreate([
                        'name' => $term['name'],
                        'slug' => $term['slug'],
                    ]);
                    array_push($dealerfeatures, $feature->id);
                }

            }
        }

        $dealer->categories()->sync($dealerCats);
        $dealer->features()->sync($dealerfeatures);
        $this->imported ++;
    }

    private function processUpdateFile()
    {
        $exists = Storage::exists('auctioneerUpdate.xml');
        if(!$exists) return false;
        $this->xmlContents = Storage::get('auctioneerUpdate.xml');
        $xml = simplexml_load_string($this->xmlContents);

        foreach ( $xml->auctioneer as $auctioneer ) {
            $name = (string)$auctioneer->name;
            $dealer = Dealers::where('name', $name)->first();
            if(!$dealer) continue;
            //dd($auctioneer, $dealer);

            $phone = null;
            $email = null;
            $website = null;
            $longitude = null;
            $latitude = null;
            $address = null;
            $county = null;
            $postcode = null;
            if((string)$auctioneer->phone != '' && (string)$auctioneer->phone != 'na') $phone = (string)$auctioneer->phone;
            if((string)$auctioneer->email != '' && (string)$auctioneer->email != 'na') $email = (string)$auctioneer->email;
            if((string)$auctioneer->website != '' && (string)$auctioneer->website != 'na') $website = (string)$auctioneer->website;
            if((string)$auctioneer->long != '' && (string)$auctioneer->long != 'na' && (string)$auctioneer->long != 0) $longitude = (string)$auctioneer->long;
            if((string)$auctioneer->lat != '' && (string)$auctioneer->lat != 'na' && (string)$auctioneer->lat != 0) $latitude = (string)$auctioneer->lat;
            if((string)$auctioneer->address != '' && (string)$auctioneer->address != 'na' && (string)$auctioneer->address != 0) $address = (string)$auctioneer->address;
            if((string)$auctioneer->county != '' && (string)$auctioneer->county != 'na' && (string)$auctioneer->county != 0) $county = (string)$auctioneer->county;
            if((string)$auctioneer->postcode != '' && (string)$auctioneer->postcode != 'na' && strtolower((string)$auctioneer->postcode) != 'none') $postcode = (string)$auctioneer->postcode;

            //if((string)$auctioneer->postcode) echo (string)$auctioneer->postcode.'---'.$postcode.PHP_EOL;

            if($dealer->longitude == '' || is_null($dealer->longitude)) $dealer->longitude = $longitude;
            if($dealer->latitude == '' || is_null($dealer->latitude)) $dealer->latitude = $latitude;
            if($dealer->county == '' || is_null($dealer->county)) $dealer->county = $county;
            if($dealer->phone == '' || is_null($dealer->phone)) $dealer->phone = $phone;
            if($dealer->email == '' || is_null($dealer->email)) $dealer->email = $email;
            if($dealer->website == '' || is_null($dealer->website)) $dealer->website = $website;
            if($dealer->postcode == '' || is_null($dealer->postcode)) $dealer->postcode = $postcode;
            if($dealer->address == '' || is_null($dealer->address)) $dealer->address = $address;
            if(strtolower($county) === 'online') $dealer->online_only = 1;
            $dealer->save();

            $dealerfeatures = [];
            $features = (string)$auctioneer->features;
            if($features != '')
            {
                $features = explode(',', $features);
                foreach($features as $feature)
                {
                    $feature = DealersFeatures::firstOrCreate([
                        'name' => $feature,
                        'slug' => str_slug($feature),
                    ]);
                    array_push($dealerfeatures, $feature->id);
                }
            }
            $dealer->features()->sync($dealerfeatures);


        }
    }

    private function processImageImportFile()
    {
        $exists = Storage::exists('auctioneerImportImage.xml');
        if(!$exists) return false;
        $this->xmlContents = Storage::get('auctioneerImportImage.xml');
        $xml = simplexml_load_string($this->xmlContents);
        $namespaces = $xml->getDocNamespaces();


        foreach ( $xml->channel->item as $item ) {

            $post = [];
            $wp = $item->children( $namespaces['wp'] );
            $post['post_id'] = (int) $wp->post_id;
            $post['image'] = (string) $wp->attachment_url;

            DealerImageImport::firstOrCreate($post);

        }
        return true;
    }

    private function processImageImportDealers()
    {
        $exists = Storage::exists('auctioneerImport.xml');
        if(!$exists) return false;
        $this->xmlContents = Storage::get('auctioneerImport.xml');
        $xml = simplexml_load_string($this->xmlContents);
        $namespaces = $xml->getDocNamespaces();


        foreach ( $xml->channel->item as $item ) {

            $post = [];
            $post['post_title'] = (string) $item->title;
            $wp = $item->children( $namespaces['wp'] );
            foreach ( $wp->postmeta as $meta ) {
                if($meta->meta_key == '_thumbnail_id')
                {
                    $post['dealers_image_import_id'] = (string) $meta->meta_value;
                }
            }

            $post['post_title'] = rtrim($post['post_title'], '*');
            $existingDealer = Dealers::where('slug', str_slug($post['post_title']))->first();

            $post['dealer_id'] = $existingDealer->id;
            unset($post['post_title']);
            DealerFeaturedImageImport::create($post);

        }
        return true;
    }

    public function importActualImages()
    {
        $ImportedImages = DealerImageImport::get();
        foreach ($ImportedImages as $ImportedImage)
        {
            if(!is_null($ImportedImage->image))
            {
                $image = $this->getRemoteImage($ImportedImage->image, 'D:\xampp\htdocs\Home\Work\Gauk\gauk\public\images\featured');
                if($image)
                {
                    $ImportedImage->image = $image;
                    $ImportedImage->save();
                }
                $ImportedImage->image = $image;
                $ImportedImage->processed = 1;
                $ImportedImage->save();
            }
        }
    }

    /**
     * Get the remote image
     * @param $url
     * @param $destination
     * @return bool|mixed
     */
    private function getRemoteImage($url, $destination)
    {
        $imagecollectiontype = 'curl';
        $exploded_image_url = explode("/",$url);
        $image_filename = end($exploded_image_url);

        if($hasArgs = strpos($image_filename, "?"))
        {
            $image_filename = explode("?",$image_filename);
            $image_filename = $image_filename[0];
        } elseif($hasArgs = strpos($image_filename, "&"))
        {
            $image_filename = explode("&",$image_filename);
            $image_filename = $image_filename[0];
        }
        $exploded_image_filename = explode(".",$image_filename);
        $extension = end($exploded_image_filename);
        $extension = strtolower($extension);

        //make sure its an image
        if($extension=="gif"||$extension=="jpg"||$extension=="png"){

            if($imagecollectiontype == 'normal')
            {
                //get the remote image
                $image_to_fetch = @file_get_contents($url);


                if ($image_to_fetch === false) return false;
                //save it
                $image_filename = trim(str_replace(' ', '_', $image_filename));
                $image_filename = str_replace('%20', '_', $image_filename);
                $local_image_file = fopen($destination.'/'.$image_filename, 'w+');
                //@chmod($destination,0755);
                fwrite($local_image_file, $image_to_fetch);
                fclose($local_image_file);
                return $image_filename;
            }

            if($imagecollectiontype == 'curl')
            {
                $config["CURL_SETOPT"] = array(
                    CURLOPT_RETURNTRANSFER  => 1,
                    CURLOPT_TIMEOUT         => 60,
                    CURLOPT_FOLLOWLOCATION  => 1,
                    CURLOPT_MAXREDIRS       => 10,
                    CURLOPT_VERBOSE         => 1,
                    CURLOPT_USERAGENT       => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
                    CURLOPT_SSL_VERIFYPEER  => false,
                    CURLOPT_SSL_VERIFYHOST  => false,
                    CURLOPT_VERBOSE  => 1,
                );

                $ch = curl_init($url);
                $options = $config['CURL_SETOPT'];
                curl_setopt_array($ch, $options);
                $image_filename = trim(str_replace(' ', '_', $image_filename));
                $image_filename = str_replace('%20', '_', $image_filename);
                $local_image_file = fopen($destination.'/'.$image_filename, 'w+');
                curl_setopt($ch, CURLOPT_FILE, $local_image_file);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $result = curl_exec($ch);
                curl_close($ch);
                fclose($local_image_file);

                if($result) return $image_filename;
            }

        }
        return false;
    }

    public function setDealerFeaturedImages()
    {
        $dealers = DealerFeaturedImageImport::with('media')->get();
        foreach($dealers as $dealer)
        {
            if(!is_null($dealer->dealer_id) && !is_null($dealer->dealers_image_import_id))
            {
                DealersMedia::create([
                    'dealer_id' => $dealer->dealer_id,
                    'name' => '/images/featured/'.$dealer->media->image,
                    'type' => 'image',
                    'area' => 'featured'
                ]);
            }
        }
    }


    private function cacheClear()
    {
        \Cache::forget('auctioneers');
    }
}