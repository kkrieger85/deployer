<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/common.php';

// Flow-Framework application-context
env('flow_context', 'Production');

// Flow-Framework cli-command
env('flow_command', 'flow');

// Flow-Framework shared directories
set('shared_dirs', [
    'Data/Persistent',
    'Data/Logs',
    'Configuration/{{flow_context}}'
]);

/**
 * Apply database migrations
 */
task('deploy:run_migrations', function () {
    run('FLOW_CONTEXT={{flow_context}} {{bin/php}} {{release_path}}/{{flow_command}} doctrine:migrate');
})->desc('Apply database migrations');

/**
 * Publish resources
 */
task('deploy:publish_resources', function () {
    run('FLOW_CONTEXT={{flow_context}} {{bin/php}} {{release_path}}/{{flow_command}} resource:publish');
})->desc('Publish resources');

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:shared',
    'deploy:run_migrations',
    'deploy:publish_resources',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');
