<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/npm.php';
require 'recipe/sentry.php';

// Project name
set('application', 'Bobby');

// Project repository
set('repository', 'git@bitbucket.org:tassodelivery/bobby.git');

set('version', function () {
    $version = trim(runLocally('git log -n 1 --format="%h"'));

    return $version;
});

// Sentry versionning
set('sentry', [
    'organization' => 'tasso',
    'project' => 'bobby_frontend',
    'token' => '6620409604b6408084cc5f2772c8df501484e9daa2b3432bbb87cf449578f318'
]);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts
host('tassodelivery.com')
    ->set('deploy_path', '/home/{{application}}/web/')
    ->stage('dev');

host('tasso.today')
    ->set('deploy_path', '/home/{{application}}/web/')
    ->stage('prod');

// Tasks
task('copy:env', function () {
    $stage = null;
    if (input()->hasArgument('stage')) {
        $stage = input()->getArgument('stage');
        run('cd {{release_path}} && touch resources/assets/js/env.js');

        $envFileContent = file_get_contents('.env.' . $stage . '.js');
        $envFileContentWithVersion = str_replace('%VERSION%', get('version'), $envFileContent);
        run('echo "' . $envFileContentWithVersion . '" >> {{release_path}}/resources/assets/js/env.js');
    }
});

task('modify:permission', function () {
    run('cd {{release_path}} && chown www-data: bootstrap/cache/');
});

task('npm:run', function () {
    run('cd {{release_path}} && {{bin/npm}} run {{stage}}');
});

task('frontend:build', [
    'npm:install',
    'copy:env',
    'modify:permission',
    'npm:run'
]);

task('deploy:sentry-prod', [
    'deploy:sentry'
])->onStage('prod');

// Build frontend
before('deploy:symlink', 'frontend:build');

// Sentry versionning
after('deploy', 'deploy:sentry-prod');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');
