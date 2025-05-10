<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li><a href="<?= url('api-documentation') ?>"><?= l('api_documentation.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('archived_audits.title') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h4 mb-4"><?= l('archived_audits.title') ?></h1>

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
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/archived-audits/</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/archived-audits/' \<br />
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
            "audit_archive_id": 1,
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
            "score": 90,
            "total_issues": 10,
            "major_issues": 4,
            "moderate_issues": 1,
            "minor_issues": 5,
            "total_refreshes": 10,
            "expiration_datetime": "2025-03-08 18:50:45",
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
        "first": "<?= SITE_URL ?>api/archived-audits?&page=1",
        "last": "<?= SITE_URL ?>api/archived-audits?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/archived-audits?&page=1"
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
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/archived-audits/</span><span class="text-primary">{archived_audit_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/archived-audits/<span class="text-primary">{archived_audit_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 1
        "audit_archive_id": 1,
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
        "score": 90,
        "total_issues": 10,
        "major_issues": 4,
        "moderate_issues": 1,
        "minor_issues": 5,
        "total_refreshes": 10,
        "expiration_datetime": "2025-03-08 18:50:45",
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#archived_audits_delete" aria-expanded="true" aria-controls="archived_audits_delete">
                        <?= l('api_documentation.delete') ?>
                    </a>
                </h3>
            </div>

            <div id="archived_audits_delete" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>api/archived-audits/</span><span class="text-primary">{archived_audit_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request DELETE \<br />
                                --url '<?= SITE_URL ?>api/archived-audits/<span class="text-primary">{archived_audit_id}</span>' \<br />
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

