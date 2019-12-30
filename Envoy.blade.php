@include('envoy.config.php');

@servers(['gaukauctions' => $ssh['gaukauctions']['server']])

@setup
$now = new DateTime();
$date = $now->format('YmdHis');
$env = isset($env) ? $env : "local";
$branch = isset($branch) ? $branch : "master";
$server = isset($server) ? $server : "gaukauctions";
@endsetup

@task('test', ['on' => $server])
echo "Server: {{$server}}";
ls -la
@endtask

@task('init', ['on' => $server])
rm -fr {{ $ssh[$server]['path'] }};
echo "Setting up directories";
mkdir {{ $ssh[$server]['path'] }};
mkdir {{ $ssh[$server]['path'] }}/releases;
cd {{ $ssh[$server]['path'] }}/releases;
/usr/local/cpanel/3rdparty/bin/git clone {{ $repo }} --branch={{ $branch }} --depth=1 current;
echo "Repository cloned";
chmod -R 0777 current/storage;
chmod -R 0777 current/bootstrap/cache;
echo "Moving Files into {{ $ssh[$server]['publicPath'] }}";
cp -R current/public/* {{ $ssh[$server]['publicPath'] }}/;
echo "Initial Move Done";
rm -fr {{ $ssh[$server]['publicPath'] }}/index.php;
mv {{ $ssh[$server]['publicPath'] }}/index.{{ $server }}.php {{ $ssh[$server]['publicPath'] }}/index.php
yes | cp -a current/public/.htaccess {{ $ssh[$server]['publicPath'] }}/.htaccess
echo "Public Files Moved";
touch {{ $ssh[$server]['path'] }}/.env;
ln -s {{ $ssh[$server]['path'] }}/.env current/.env;
echo "Environment file created";
cd current;
/home/gaukauctions/composer.phar install;
echo "Initial deployment complete";
echo "once the env file has been modified please run";
echo "cd {{ $ssh[$server]['path'] }}/current && php artisan migrate --env=local --force --no-interaction";
@endtask


@task('deploy', ['on' => 'gaukauctions'])
cd {{ $ssh['gaukauctions']['path'] }}/releases;
rm -fr current_old
/usr/local/cpanel/3rdparty/bin/git clone {{ $repo }} --branch={{ $branch }} --depth=1 currentNew;
echo "Repository cloned";
ln -s {{ $ssh['gaukauctions']['path'] }}/.env currentNew/.env;
echo "Env Linked";
cp -fr current/storage/* currentNew/storage/
cp -fr current/resources/views/emails/* currentNew/resources/views/emails/
yes | cp -R currentNew/public/* {{ $ssh['gaukauctions']['publicPath'] }}/;
rm -fr {{ $ssh['gaukauctions']['publicPath'] }}/index.php;
mv {{ $ssh['gaukauctions']['publicPath'] }}/index.gaukauctions.php {{ $ssh['gaukauctions']['publicPath'] }}/index.php
mv {{ $ssh['gaukauctions']['path'] }}/releases/currentNew/config/lfm-GAUK.php {{ $ssh['gaukauctions']['path'] }}/releases/currentNew/config/lfm.php
echo "Public DIR copied";
cd currentNew
cd storage/framework/cache
rm -fr data
mkdir data
cd ../../../
/home/gaukauctions/composer.phar install --no-interaction --no-dev;
php artisan migrate --env=local --force --no-interaction
cd ..
mv current current_old
mv currentNew current
cd current
php artisan quantum:install
php artisan route:cache
php artisan cache:clear
php artisan view:clear
php artisan queue:restart
echo "Deployment complete";
@endtask