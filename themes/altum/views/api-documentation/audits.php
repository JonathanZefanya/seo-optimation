<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li><a href="<?= url('api-documentation') ?>"><?= l('api_documentation.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('audits.title') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h4 mb-4"><?= l('audits.title') ?></h1>

    <div class="accordion">
        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#read_all" aria-expanded="true" aria-controls="read_all">
                        <?= l('api_documentation.read_all') ?>
                    </a>
                </h3>
            </div>

            <div id="read_all" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/audits/</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/audits/' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container mb-4">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('api_documentation.parameters') ?></th>
                                <th><?= l('global.details') ?></th>
                                <th><?= l('global.description') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>page</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-hashtag mr-1"></i> <?= l('api_documentation.int') ?></span>
                                </td>
                                <td><?= l('api_documentation.filters.page') ?></td>
                            </tr>
                            <tr>
                                <td>results_per_page</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-hashtag mr-1"></i> <?= l('api_documentation.int') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.filters.results_per_page'), '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250, 500, 1000]) . '</code>', 25) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": [
        {
            "id": 1,
            "website_id": 1,
            "domain_id": 0,
            "user_id": 1,
            "uploader_id": "f528764d624db129b32c21fbca0cb8d6",
            "host": "example.com",
            "url": "https://example.com",
            "ttfb": 0.3,
            "response_time": 500,
            "average_download_speed": 200,
            "page_size": 5000,
            "is_https": true,
            "is_ssl_valid": true,
            "http_protocol": "2",
            "title": "Example title",
            "meta_description": "Example description.",
            "meta_keywords": "example, keywords",
            "data": {...},
            "issues": {
              "major": [],
              "moderate": {
                "meta_charset": [
                  "missing"
                ],
                "is_http2": [
                  "invalid"
                ],
                "external_links": [
                  "too_many"
                ]
              },
              "minor": {
                "deprecated_html_tags": [
                  "existing"
                ],
                "header_server": [
                  "existing"
                ],
                "inline_css": [
                  "existing"
                ],
                "unsafe_external_links": [
                  "existing"
                ],
                "non_deferred_scripts": [
                  "existing"
                ]
              },
              "potential_major_issues": 11,
              "potential_moderate_issues": 14,
              "potential_minor_issues": 15,
              "total_tests": 40,
              "passed_tests": 32
            },
            "settings": {
              "is_public": 1,
              "password": "$2y$10$eNkOWNTptDV.Ye4v28FmO.nCWqus5SE.mYBUSzajujKfk/NNehXxe",
              "audit_check_interval": 21600
            },
            "notifications": [
              1
            ],
            "score": 90,
            "total_issues": 10,
            "major_issues": 4,
            "moderate_issues": 1,
            "minor_issues": 5,
            "total_refreshes": 10,
            "refresh_error": null,
            "next_refresh_datetime": null,
            "last_refresh_datetime": "2024-12-08 18:50:46",
            "expiration_datetime": "2025-03-08 18:50:45",
            "last_datetime": null,
            "datetime": "<?= get_date() ?>"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/audits?&page=1",
        "last": "<?= SITE_URL ?>api/audits?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/audits?&page=1"
    }
}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#read" aria-expanded="true" aria-controls="read">
                        <?= l('api_documentation.read') ?>
                    </a>
                </h3>
            </div>

            <div id="read" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/audits/</span><span class="text-primary">{audit_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/audits/<span class="text-primary">{audit_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 1,
        "website_id": 1,
        "domain_id": 0,
        "user_id": 1,
        "uploader_id": "f528764d624db129b32c21fbca0cb8d6",
        "host": "example.com",
        "url": "https://example.com",
        "ttfb": 0.3,
        "response_time": 500,
        "average_download_speed": 200,
        "page_size": 5000,
        "is_https": true,
        "is_ssl_valid": true,
        "http_protocol": "2",
        "title": "Example title",
        "meta_description": "Example description.",
        "meta_keywords": "example, keywords",
        "data": {...},
        "issues": {
          "major": [],
          "moderate": {
            "meta_charset": [
              "missing"
            ],
            "is_http2": [
              "invalid"
            ],
            "external_links": [
              "too_many"
            ]
          },
          "minor": {
            "deprecated_html_tags": [
              "existing"
            ],
            "header_server": [
              "existing"
            ],
            "inline_css": [
              "existing"
            ],
            "unsafe_external_links": [
              "existing"
            ],
            "non_deferred_scripts": [
              "existing"
            ]
          },
          "potential_major_issues": 11,
          "potential_moderate_issues": 14,
          "potential_minor_issues": 15,
          "total_tests": 40,
          "passed_tests": 32
        },
        "settings": {
          "is_public": 1,
          "password": "$2y$10$eNkOWNTptDV.Ye4v28FmO.nCWqus5SE.mYBUSzajujKfk/NNehXxe",
          "audit_check_interval": 21600
        },
        "notifications": [
          1
        ],
        "score": 90,
        "total_issues": 10,
        "major_issues": 4,
        "moderate_issues": 1,
        "minor_issues": 5,
        "total_refreshes": 10,
        "refresh_error": null,
        "next_refresh_datetime": null,
        "last_refresh_datetime": "2024-12-08 18:50:46",
        "expiration_datetime": "2025-03-08 18:50:45",
        "last_datetime": null,
        "datetime": "<?= get_date() ?>"
  }
}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#audits_create" aria-expanded="true" aria-controls="audits_create">
                        <?= l('api_documentation.create') ?>
                    </a>
                </h3>
            </div>

            <div id="audits_create" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/audits</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container mb-4">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('api_documentation.parameters') ?></th>
                                <th><?= l('global.details') ?></th>
                                <th><?= l('global.description') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>type</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-signature mr-1"></i> <?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['single', 'sitemap', 'bulk', 'html'])) ?></td>
                            </tr>
                            <tr>
                                <td>url</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-signature mr-1"></i> <?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = single, sitemap, html</span>') ?></td>
                            </tr>
                            <tr>
                                <td>urls</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-signature mr-1"></i> <?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = bulk</span>') ?></td>
                            </tr>
                            <tr>
                                <td>domain_id</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-hashtag mr-1"></i> <?= l('api_documentation.int') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>password</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-signature mr-1"></i> <?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>

                            <tr>
                                <td>is_public</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-toggle-on mr-1"></i> <?= l('api_documentation.boolean') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>check_interval_seconds</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-hashtag mr-1"></i> <?= l('api_documentation.int') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys(require APP_PATH . 'includes/audits_check_intervals.php')) . '</code>') ?> <?= '(' . l('global.date.seconds') . ')' ?></td>
                            </tr>
                            <tr>
                                <td>notifications</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-list mr-1"></i> <?= l('api_documentation.array') ?></span>
                                </td>
                                <td><?= l('api_documentation.notifications_handlers_ids') ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>api/audits' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'url=<span class="text-primary">https://example.com/</span>' \<br />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "ids": [1]
    }
}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#audits_update" aria-expanded="true" aria-controls="audits_update">
                        <?= l('api_documentation.update') ?>
                    </a>
                </h3>
            </div>

            <div id="audits_update" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/audits/</span><span class="text-primary">{audit_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container mb-4">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('api_documentation.parameters') ?></th>
                                <th><?= l('global.details') ?></th>
                                <th><?= l('global.description') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>domain_id</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-hashtag mr-1"></i> <?= l('api_documentation.int') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>password</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-signature mr-1"></i> <?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>is_public</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-toggle-on mr-1"></i> <?= l('api_documentation.boolean') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>check_interval_seconds</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-hashtag mr-1"></i> <?= l('api_documentation.int') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys(require APP_PATH . 'includes/audits_check_intervals.php')) . '</code>') ?> <?= '(' . l('global.date.seconds') . ')' ?></td>
                            </tr>
                            <tr>
                                <td>notifications</td>
                                <td>
                                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-circle-notch mr-1"></i> <?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-list mr-1"></i> <?= l('api_documentation.array') ?></span>
                                </td>
                                <td><?= l('api_documentation.notifications_handlers_ids') ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>api/audits/<span class="text-primary">{audit_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'is_public=<span class="text-primary">0</span>' \<br />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 1
    }
}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#audits_delete" aria-expanded="true" aria-controls="audits_delete">
                        <?= l('api_documentation.delete') ?>
                    </a>
                </h3>
            </div>

            <div id="audits_delete" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>api/audits/</span><span class="text-primary">{audit_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request DELETE \<br />
                                --url '<?= SITE_URL ?>api/audits/<span class="text-primary">{audit_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require THEME_PATH . 'views/partials/shiki_highlighter.php' ?>

