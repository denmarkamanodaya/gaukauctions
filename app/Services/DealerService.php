<?php namespace App\Services;

use App\Models\Dealers;
use App\Models\DealersAddresses;
use App\Models\DealersMedia;
use App\Models\DealersUser;
use App\Models\Postcode;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Laracasts\Flash\Flash;

/**
 * Class AuctioneerService
 * @package App\Services
 */
class DealerService {


    public function __construct()
    {
    }

    /**
     * Save a new auctioneer to the database
     *
     * @param $request
     */
    public function createNewAuctioneer($request, $type='Auctioneer')
    {
        if($request['postcode'] != '')
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }
        //create auctioneer
        $auctioneer = new Dealers();
        $auctioneer->name = trim($request['name']);
        $auctioneer->address = trim($request['address']);
        $auctioneer->country = trim($request['country']);
        $auctioneer->postcode = trim($request['postcode']);
        $auctioneer->longitude = isset($longitude) ? $longitude : '';
        $auctioneer->latitude = isset($latitude) ? $latitude : '';
        $auctioneer->town = trim($request['town']);
        $auctioneer->county = trim($request['county']);
        $auctioneer->phone = trim($request['phone']);
        $auctioneer->fax = isset($request['fax']) ? trim($request['fax']) : '';
        $auctioneer->email = isset($request['email']) ? trim($request['email']) : '';
        $auctioneer->website = isset($request['website']) ? trim($request['website']) : '';
        $auctioneer->auction_url = isset($request['auction_url']) ? trim($request['auction_url']) : '';
        $auctioneer->online_bidding_url = isset($request['online_bidding_url']) ? trim($request['online_bidding_url']) : '';
        $auctioneer->status = trim($request['status']);
        $auctioneer->details = trim($request['details']);
        $auctioneer->buyers_premium = isset($request['buyers_premium']) ? trim($request['buyers_premium']) : '';
        $auctioneer->directions = isset($request['directions']) ? trim($request['directions']) : '';
        $auctioneer->rail_station = isset($request['rail_station']) ? trim($request['rail_station']) : '';
        $auctioneer->notes = trim($request['notes']);
        $auctioneer->category_id = trim($request['category_id']);
        $auctioneer->to_parse = isset($request['to_parse']) ? $request['to_parse'] : 0;

        $auctioneer->save();

        $auctioneer->logo = $this->logoPicture($auctioneer,$request);
        $auctioneer->save();
        //images
        $this->featured_image($auctioneer, $request);
        $this->hero_image($auctioneer, $request);
        //Log, feedback and return
        Flash::success($type.' has been created.');
        $this->clearCache();
        return;
    }

    /**
     * Update the auctioneer
     *
     * @param $id
     * @param $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function updateDealer($id, $request, $type='auctioneer')
    {

        //get dealer
        $dealer = Dealers::findOrFail($id);


        //set defaults
        $longitude  = $request->longitude;
        $latitude   = $request->latitude;

        if(($request['postcode'] != '' && is_null($longitude)) || ($request['postcode'] != '' && is_null($latitude)))
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }


        $county = $request->county;

        if($request->online_only)
        {
            $county = 'Online';
        }

        //do updates
        $dealer->name = $request['name'];
        $dealer->logo = $this->logoPicture($dealer, $request);
        $dealer->address = $request->address;
        $dealer->town = $request->town;
        $dealer->county = $county;
        $dealer->postcode = $request->postcode;
        $dealer->country_id = $request['country_id'];
        $dealer->longitude = $longitude;
        $dealer->latitude = $latitude;
        $dealer->phone = $request['phone'];
        $dealer->email = $request['email'];
        $dealer->website = $request['website'];
        $dealer->auction_url = $request['auction_url'];
        $dealer->online_bidding_url = $request['online_bidding_url'];
        $dealer->status = $request['status'];
        $dealer->details = $request['details'];
        $dealer->buyers_premium = $request['buyers_premium'];
        $dealer->directions = $request['directions'];
        $dealer->notes = $request['notes'];
        $dealer->online_only = $request['online_only'];
        $dealer->to_parse = isset($request['to_parse']) ? $request['to_parse'] : 0;

        $dealer->save();

        if(!$request['categories']) $request['categories'] = [];
        $dealer->categories()->sync($request['categories']);

        if(!$request['features']) $request['features'] = [];
        $dealer->features()->sync($request['features']);

        //favourites and calendar
        if($request->status == 'hidden' || $request->status == 'inactive')
        {
            DealersUser::where('dealers_id', $dealer->id)->delete();
            $dealer->calendarEvents()->update(['status' => 'inactive']);
        } else {
            $dealer->calendarEvents()->update(['status' => 'active']);
        }

        //images
        $this->featured_image($dealer, $request);
        $this->hero_image($dealer, $request);

        //Log, feedback and return
        Flash::success(ucfirst($type).' has been updated.');
        $this->clearCache();
        return $dealer;
    }

    private function featured_image($auctioneer, $request)
    {
        $path = dealer_logo_path($auctioneer->id);

        if($request['remove_featured_image'])
        {
            DealersMedia::where('dealer_id', $auctioneer->id)->where('area', 'featured')->delete();
        }

        if($request->file('featured_image'))
        {
            $featuredImage = $request->file('featured_image')->getClientOriginalName();
            $featuredImage = str_replace(' ', '_', $featuredImage);

            $image = Image::make($request->file('featured_image')->getRealPath());

            File::exists($path) or File::makeDirectory($path);

            //Save new
            $image->save($path. $featuredImage);

            DealersMedia::where('dealer_id', $auctioneer->id)->where('area', 'featured')->delete();
            DealersMedia::create([
                'dealer_id' => $auctioneer->id,
                'name' => '/images/dealers/'.$auctioneer->id.'/'.$featuredImage,
                'type' => 'image',
                'area' => 'featured'
            ]);
        }

    }

    private function hero_image($auctioneer, $request)
    {
        $path = dealer_logo_path($auctioneer->id);

        if($request['remove_hero_image'])
        {
            DealersMedia::where('dealer_id', $auctioneer->id)->where('area', 'hero')->delete();
        }

        if($request->file('hero_image'))
        {
            $heroImage = $request->file('hero_image')->getClientOriginalName();
            $heroImage = str_replace(' ', '_', $heroImage);

            $image = Image::make($request->file('hero_image')->getRealPath());

            File::exists($path) or File::makeDirectory($path);

            //Save new
            $image->save($path. $heroImage);

            DealersMedia::where('dealer_id', $auctioneer->id)->where('area', 'hero')->delete();
            DealersMedia::create([
                'dealer_id' => $auctioneer->id,
                'name' => '/images/dealers/'.$auctioneer->id.'/'.$heroImage,
                'type' => 'image',
                'area' => 'hero'
            ]);
        }

    }


    /**
     * Save the logo picture
     *
     * @param $auctioneer
     * @param $request
     * @return string
     */
    private function logoPicture($auctioneer, $request)
    {
        $logoPic = isset($request['logo'])? $request['logo'] : $auctioneer->logo;
        $path = dealer_logo_path($auctioneer->id);
        if($request['delPicture'])
        {
            $this->deleteLogoImages($path, $auctioneer);
            $logoPic = null;
        }

        if($request->file('logo'))
        {

            $logoPic = $request->file('logo')->getClientOriginalName();
            $logoPic = str_replace(' ', '_', $logoPic);

            $image = Image::make($request->file('logo')->getRealPath());

            File::exists($path) or File::makeDirectory($path);

            if($logoPic != '')
            {
                $this->deleteLogoImages($path, $auctioneer);
            }

            //Save new
            $image->save($path. $logoPic);

            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb300-'.$logoPic);

            $image->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb150-'.$logoPic);

            $image->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb100-'.$logoPic);

            $image->resize(50, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb50-'.$logoPic);

        }
        return $logoPic;
    }

    /**
     * Delete logo picture
     *
     * @param $path
     * @param $auctioneer
     */
    private function deleteLogoImages($path, $auctioneer)
    {
        //remove old images
        File::delete($path . $auctioneer->logo);
        File::delete($path .'/thumb300-'. $auctioneer->logo);
        File::delete($path .'/thumb150-'. $auctioneer->logo);
        File::delete($path .'/thumb100-'. $auctioneer->logo);
        File::delete($path .'/thumb50-'. $auctioneer->logo);
    }

    public function cleanImageSpaces()
    {
        $dealers = Dealers::get();
        foreach($dealers as $dealer)
        {
            if(is_null($dealer->logo) || $dealer->logo == '') continue;
            if ( preg_match('/\s/',$dealer->logo) ) {
                $path = dealer_logo_path($dealer->id);
                $logoNew = str_replace(' ', '_', $dealer->logo);
                File::move($path . $dealer->logo, $path.$logoNew);
                File::move($path .'/thumb300-'. $dealer->logo, $path.'/thumb300-'.$logoNew);
                File::move($path .'/thumb150-'. $dealer->logo, $path.'/thumb150-'.$logoNew);
                File::move($path .'/thumb100-'. $dealer->logo, $path.'/thumb100-'.$logoNew);
                File::move($path .'/thumb50-'. $dealer->logo, $path.'/thumb50-'.$logoNew);
                $dealer->logo = $logoNew;
                $dealer->save();
            }
        }
        $this->clearCache();
    }

    /**
     * Delete all auctioneer images
     *
     * @param $auctioneer
     */
    private function deleteAllImages($auctioneer)
    {
        File::delete(dealer_logo_path($auctioneer->id));
    }

    public function createDealer($request, $type='auctioneer')
    {
        //set defaults
        $longitude  = $request->longitude;
        $latitude   = $request->latitude;

        if($request['postcode'] != '' && is_null($longitude) && is_null($latitude))
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }


        $county = $request->county;

        if($request->online_only)
        {
            $county = 'Online';
        }

        //do updates
        $dealer = new Dealers();
        $dealer->name = $request['name'];

        $dealer->address = $request->address;
        $dealer->town = $request->town;
        $dealer->county = $county;
        $dealer->postcode = $request->postcode;
        $dealer->country_id = $request['country_id'];
        $dealer->longitude = $longitude;
        $dealer->latitude = $latitude;
        $dealer->phone = $request['phone'];
        $dealer->email = $request['email'];
        $dealer->website = $request['website'];
        $dealer->auction_url = $request['auction_url'];
        $dealer->online_bidding_url = $request['online_bidding_url'];
        $dealer->status = $request['status'];
        $dealer->details = $request['details'];
        $dealer->buyers_premium = $request['buyers_premium'];
        $dealer->directions = $request['directions'];
        $dealer->notes = $request['notes'];
        $dealer->online_only = $request['online_only'];
        $dealer->to_parse = isset($request['to_parse']) ? $request['to_parse'] : 0;
        $dealer->type = $type;

        $dealer->save();

        $logopic = $this->logoPicture($dealer, $request);
        if($logopic) {
            $dealer->logo = $logopic;
            $dealer->save();
        }

        if(!$request['categories']) $request['categories'] = [];
        $dealer->categories()->sync($request['categories']);

        if(!$request['features']) $request['features'] = [];
        $dealer->features()->sync($request['features']);

        //Log, feedback and return
        Flash::success(ucfirst($type).' has been created.');
        $this->clearCache();
        return $dealer;
    }

    public function getDealerBySlug($slug)
    {
        return Dealers::where('slug', $slug)->firstOrFail();
    }

    public function imageUpload($request, $auctioneer)
    {
        $auctioneer = $this->getDealerBySlug($auctioneer);

        $path = dealer_logo_path($auctioneer->id);

        $dealerPic = $request->file('file')->getClientOriginalName();
        $dealerPic = $this->cleanName($dealerPic);
        $extension = $request->file('file')->getClientOriginalExtension();
        $image = \Image::make($request->file('file')->getRealPath());

        //Save new
        $image->save($path.$dealerPic);

        $media = $auctioneer->media()->create([
            'name' => $dealerPic,
            'type' => 'image',
            'area' => 'gallery'
        ]);
        \Activitylogger::log('Dealer Image Uploaded : '.$auctioneer->name.' : '.$media->name, $media);
        $this->clearCache();
        return $dealerPic;

    }

    private function cleanName($fileName)
    {
        $fileName = str_replace(' ', '_', $fileName);
        $fileName = str_replace('\'', '', $fileName);
        $fileName = str_replace('`', '', $fileName);
        return $fileName;
    }

    public function deleteGalleryImage($request, $dealer)
    {
        $dealer = $this->getDealerBySlug($dealer);
        $media = DealersMedia::where('dealer_id', $dealer->id)->where('id', $request->image)->where('area', 'gallery')->firstOrFail();
        $path = dealer_logo_path($dealer->id);
        @unlink($path.$media->name);
        $media->delete();
        \Activitylogger::log('Dealer Image Deleted : '.$dealer->name.' : '.$media->name, $media);
        $this->clearCache();
    }

    public function deleteDealer($slug)
    {
        $dealer = $this->getDealerBySlug($slug);
        $path = rtrim(dealer_logo_path($dealer->id), '/');
        $dealer->media()->delete();
        array_map('unlink', glob("$path/*.*"));
        File::delete($path);
        $dealer->remind()->delete();
        $dealer->delete();
        \Activitylogger::log('Dealer Deleted : '.$dealer->name, $dealer);
        flash('Dealer has been deleted.')->success();
        $this->clearCache();
    }

    public function clearCache()
    {
        \App\Services\CacheService::clearDealers();
    }

    public function createAddress($request, $slug)
    {
        $dealer = $this->getDealerBySlug($slug);

        //set defaults
        $longitude  = $request->longitude;
        $latitude   = $request->latitude;

        if($request['postcode'] != '' && is_null($longitude) && is_null($latitude))
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }

        $address = new DealersAddresses();
        $address->dealers_id = $dealer->id;
        $address->name = $request['name'];

        $address->address = $request->address;
        $address->town = $request->town;
        $address->county = $request->county;
        $address->postcode = $request->postcode;
        $address->country_id = $request->country_id;
        $address->longitude = $longitude;
        $address->latitude = $latitude;
        $address->phone = $request->phone;
        $address->auction_url = $request->auction_url;
        $address->save();

        \Activitylogger::log('Dealer Address Created : '.$address->name, $address);
        flash('Dealer Address has been created.')->success();
        $this->clearCache();
        return $address;
    }

    public function editAddress($request, $slug, $addressId)
    {
        $dealer = $this->getDealerBySlug($slug);
        $address = DealersAddresses::where('dealers_id', $dealer->id)->where('id', $addressId)->firstOrFail();

        //set defaults
        $longitude  = $request->longitude;
        $latitude   = $request->latitude;

        if($request['postcode'] != '' && is_null($longitude) && is_null($latitude))
        {
            if($geo_location = Postcode::postcode($request['postcode'])->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }

        $address->name = $request['name'];
        $address->address = $request->address;
        $address->town = $request->town;
        $address->county = $request->county;
        $address->postcode = $request->postcode;
        $address->country_id = $request->country_id;
        $address->longitude = $longitude;
        $address->latitude = $latitude;
        $address->phone = $request->phone;
        $address->auction_url = $request->auction_url;
        $address->save();

        \Activitylogger::log('Dealer Address Created : '.$address->name, $address);
        flash('Dealer Address has been updated.')->success();
        $this->clearCache();
        return $address;
    }

    public function deleteAddress($slug, $addressId)
    {
        $dealer = $this->getDealerBySlug($slug);
        $address = DealersAddresses::where('dealers_id', $dealer->id)->where('id', $addressId)->firstOrFail();
        $address->delete();
        \Activitylogger::log('Dealer Address Deleted : '.$address->name, $address);
        flash('Dealer Address has been deleted.')->success();
        $this->clearCache();
    }

    public function getAddress($request, $slug)
    {
        $dealer = $this->getDealerBySlug($slug);
        if($request->addresses == 0) return $dealer;
        $address = DealersAddresses::where('dealers_id', $dealer->id)->where('id', $request->addresses)->firstOrFail();
        return $address;
    }

}