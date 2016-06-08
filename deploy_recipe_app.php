<?php

require __DIR__ . '/vendor/deployer/deployer/recipe/symfony3.php';

task('deploy:fos:js-routing:dump', function () {

    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console fos:js-routing:dump  --env={{env}} --no-debug');

})->desc('FOS JS Routing Dump');

after('deploy:vendors', 'deploy:fos:js-routing:dump');

task('deploy:assetic:dump', function () {
    if (!get('dump_assets')) {
        return;
    }

    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console assets:install --env={{env}} --no-debug {{release_path}}/web');

    run('{{bin/php}} {{release_path}}/' . trim(get('bin_dir'), '/') . '/console assetic:dump --env={{env}} --no-debug');

})->desc('Dump assets');

$shared_dir = get('shared_dirs');
$shared_dir[] = 'web/images/profile';
set('shared_dirs', $shared_dir);