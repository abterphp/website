--
-- Table data for table `admin_resources`
--

INSERT INTO `admin_resources` (`id`, `identifier`)
VALUES (UUID(), 'block-layouts'),
       (UUID(), 'page-layouts'),
       (UUID(), 'pages'),
       (UUID(), 'blocks'),
       (UUID(), 'page-categories'),
       (UUID(), 'lists');

--
-- Table data for table `casbin_rule`
--

INSERT INTO `casbin_rule` (`ptype`, `v0`, `v1`, `v2`)
VALUES ('p', 'admin', 'pages', 'advanced-write'),
       ('p', 'admin', 'blocks', 'advanced-write'),
       ('p', 'admin', 'lists', 'advanced-write'),
       ('p', 'layout-editor', 'pages', 'advanced-write'),
       ('p', 'layout-editor', 'blocks', 'advanced-write'),
       ('p', 'layout-editor', 'lists', 'advanced-write');

--
-- Table data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `identifier`, `name`)
VALUES (UUID(), 'content-editor', 'Content Editor'),
       (UUID(), 'layout-editor', 'Layout Editor');

--
-- Table structure and data for table `block_layouts`
--

CREATE TABLE `block_layouts`
(
    `id`         char(36)     NOT NULL,
    `name`       varchar(160) NOT NULL,
    `identifier` varchar(160) NOT NULL,
    `body`       mediumtext   NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `block_layouts_identifier_uindex` (`identifier`),
    KEY `block_layouts_deleted_at_index` (`deleted_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `block_layouts` (`id`, `name`, `identifier`, `body`)
VALUES (UUID(), 'Index Text Section', 'index-text-section',
        '                <div><hr class=\"section-heading-spacer\"></div>\r\n                <div class=\"clearfix\"></div>\r\n                <h2 class=\"section-heading\">{{var/title}}</h2>\r\n                <div class=\"lead\">{{var/lead}}</div>\r\n                <div class=\"body\">{{var/body}}</div>'),
       (UUID(), 'Empty', 'empty', '{{var/body}}');

--
-- Table structure and data for table `blocks`
--

CREATE TABLE `blocks`
(
    `id`         char(36)     NOT NULL,
    `title`      varchar(120) NOT NULL,
    `identifier` varchar(160) NOT NULL,
    `body`       mediumtext   NOT NULL,
    `layout_id`  char(36)     NULL,
    `layout`     mediumtext   NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `blocks_identifier` (`identifier`),
    KEY `blocks_deleted_at_index` (`deleted_at`),
    KEY `block_layouts_id_fk` (`layout_id`),
    CONSTRAINT `blocks_layouts_id_fk` FOREIGN KEY (`layout_id`) REFERENCES `block_layouts` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure and data for table `list_types`
--

CREATE TABLE `list_types`
(
    `id`         char(36)     NOT NULL,
    `name`       varchar(160) NOT NULL,
    `label`      varchar(255) NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `list_types_name_uindex` (`name`),
    KEY `list_types_deleted_at_index` (`deleted_at`)
) ENGINE = InnoDB;

INSERT INTO `list_types` (`id`, `name`, `label`)
VALUES ('0c9e1ec1-5503-4632-8fe1-4907a4df3dba', 'empty', 'website:contentListTypeEmpty'),
       ('ecf88c10-2872-4c68-9601-c88a106c49c7', 'ordered', 'website:contentListTypeOrdered'),
       ('7c85d0e4-9998-4ed8-bbbd-1a9fc769d23c', 'unordered', 'website:contentListTypeUnordered'),
       ('9ba8d47c-ab75-4f73-af01-0a82d3372622', 'natural', 'website:contentListTypeNeutral'),
       ('e6069f1e-0500-4aac-a494-79a5f9df1270', 'section', 'website:contentListTypeSection');

--
-- Table structure and data for table `lists`
--

CREATE TABLE `lists`
(
    `id`         char(36)     NOT NULL,
    `type_id`    char(36)     NOT NULL,
    `name`       varchar(160) NOT NULL,
    `identifier` varchar(160) NOT NULL,
    `classes`    varchar(255) NOT NULL,
    `protected`  bool         NOT NULL,
    `with_links` bool         NOT NULL,
    `with_image` bool         NOT NULL,
    `with_body`  bool         NOT NULL,
    `with_html`  bool         NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `lists_identifier_uindex` (`identifier`),
    KEY `lists_deleted_at_index` (`deleted_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure and data for table `list_items`
--

CREATE TABLE `list_items`
(
    `id`         char(36)     NOT NULL,
    `list_id`    char(36)     NULL,
    `name`       varchar(160) NOT NULL,
    `name_href`  mediumtext,
    `body`       mediumtext   NOT NULL,
    `body_href`  mediumtext,
    `img_src`    mediumtext,
    `img_href`   mediumtext,
    `img_alt`    mediumtext,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `list_items_deleted_at_index` (`deleted_at`),
    KEY `list_items_id_fk` (`list_id`),
    CONSTRAINT `list_items_id_fk` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure and data for table `page_categories`
--

CREATE TABLE `page_categories`
(
    `id`         char(36)     NOT NULL,
    `name`       varchar(120) NOT NULL,
    `identifier` varchar(160) NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `page_categories_identifier_uindex` (`identifier`),
    KEY `page_categories_deleted_at_index` (`deleted_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure and data for table `page_layouts`
--

CREATE TABLE `page_layouts`
(
    `id`         char(36)     NOT NULL,
    `name`       varchar(160) NOT NULL,
    `identifier` varchar(160) NOT NULL,
    `classes`    mediumtext   NOT NULL,
    `body`       mediumtext   NOT NULL,
    `header`     mediumtext   NOT NULL,
    `footer`     mediumtext   NOT NULL,
    `css_files`  mediumtext   NOT NULL,
    `js_files`   mediumtext   NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` datetime              DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `page_layouts_identifier_uindex` (`identifier`),
    KEY `page_layouts_deleted_at_index` (`deleted_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `page_layouts` (`id`, `name`, `identifier`, `classes`, `body`, `header`, `footer`, `css_files`, `js_files`)
VALUES (UUID(), 'Empty', 'empty', '{{var/body}}', '', '', '', '', '');

--
-- Table structure and data for table `pages`
--

CREATE TABLE `pages`
(
    `id`                  char(36)            NOT NULL,
    `identifier`          varchar(160)        NOT NULL,
    `classes`             mediumtext          NOT NULL,
    `title`               varchar(255)        NOT NULL,
    `meta_description`    mediumtext          NOT NULL,
    `meta_robots`         varchar(100)        NOT NULL,
    `meta_author`         varchar(160)        NOT NULL,
    `meta_copyright`      varchar(160)        NOT NULL,
    `meta_keywords`       varchar(255)        NOT NULL,
    `meta_og_title`       varchar(255)        NOT NULL,
    `meta_og_image`       varchar(255)        NOT NULL,
    `meta_og_description` mediumtext          NOT NULL,
    `lead`                mediumtext          NOT NULL,
    `body`                mediumtext          NOT NULL,
    `is_draft`            tinyint(1) unsigned NOT NULL,
    `category_id`         char(36)            NULL,
    `layout_id`           char(36)            NULL,
    `layout`              mediumtext          NOT NULL,
    `header`              mediumtext          NOT NULL,
    `footer`              mediumtext          NOT NULL,
    `css_files`           mediumtext          NOT NULL,
    `js_files`            mediumtext          NOT NULL,
    `created_at`          timestamp           NOT NULL DEFAULT current_timestamp(),
    `updated_at`          timestamp           NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at`          datetime                     DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `identifier` (`identifier`),
    KEY `pages_deleted_at_index` (`deleted_at`),
    KEY `page_layouts_id_fk` (`layout_id`),
    KEY `page_category_id_is_draft_deleted_at_index` (`category_id`, `is_draft`, `deleted_at`),
    CONSTRAINT `pages_categories_id_fk` FOREIGN KEY (`category_id`) REFERENCES `page_categories` (`id`),
    CONSTRAINT `pages_layouts_id_fk` FOREIGN KEY (`layout_id`) REFERENCES `page_layouts` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `pages` (id, identifier, classes, title, meta_description, meta_robots, meta_author, meta_copyright,
                     meta_keywords,
                     meta_og_title, meta_og_image, meta_og_description, `lead`, body, category_id, layout_id, layout,
                     header, footer, css_files, js_files, is_draft)
VALUES (UUID(), 'index', '', 'New AbterCMS installation',
        'AbterCMS is a security first, simple and flexible open source content management system for both educational and commercial usecases.',
        '', '', '', 'cms, open source', '', '', '', '', 'Hello, World!', NULL, NULL,
        '<div class="container">{{var/body}}</div>', '', '', '', '', 0);

--
-- Table structure and data for table `user_groups_page_categories`
--

CREATE TABLE `user_groups_page_categories`
(
    `id`               char(36)  NOT NULL,
    `user_group_id`    char(36)  NOT NULL,
    `page_category_id` char(36)  NOT NULL,
    `created_at`       timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at`       timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_group_id` (`user_group_id`),
    KEY `page_category_id` (`page_category_id`),
    CONSTRAINT `ugpc_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE,
    CONSTRAINT `ugpc_ibfk_2` FOREIGN KEY (`page_category_id`) REFERENCES `page_categories` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Provide admins, content editors and layouts access to all page categories
INSERT INTO `user_groups_page_categories` (`id`, `user_group_id`, `page_category_id`)
SELECT UUID(), `user_groups`.`id` AS `user_group_id`, `page_categories`.`id` AS `page_category_id`
FROM `user_groups`
         INNER JOIN `page_categories` ON 1
WHERE `user_groups`.`identifier` IN ('admin', 'content-editor', 'layout-editor');

-- Provide access to relevant admin pages for layout editors
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID(), user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
         INNER JOIN admin_resources
                    ON admin_resources.identifier IN ('block-layouts', 'page-layouts', 'pages', 'blocks', 'lists')
WHERE user_groups.identifier = 'layout-editor';

-- Provide access to relevant admin pages for content editors
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID(), user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
         INNER JOIN admin_resources ON admin_resources.identifier IN ('pages', 'blocks', 'lists')
WHERE user_groups.identifier = 'content-editor';

-- Provide admins access to all resources
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID(), user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
         INNER JOIN admin_resources ON 1
WHERE user_groups.identifier = 'admin';
