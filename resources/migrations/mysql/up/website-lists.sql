--
-- Table data for table `admin_resources`
--

INSERT INTO `admin_resources` (`id`, `identifier`)
VALUES (UUID(), 'lists');

--
-- Table data for table `casbin_rule`
--

INSERT INTO `casbin_rule` (`ptype`, `v0`, `v1`, `v2`)
VALUES ('p', 'admin', 'lists', 'advanced-write'),
       ('p', 'layout-editor', 'lists', 'advanced-write');

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
VALUES ('ecf88c10-2872-4c68-9601-c88a106c49c7', 'ordered', 'website:contentListTypeOrdered'),
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
    `with_image` bool         NOT NULL,
    `with_links` bool         NOT NULL,
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

-- Provide access to relevant admin pages
INSERT IGNORE INTO `user_groups_admin_resources` (`id`, `user_group_id`, `admin_resource_id`)
SELECT UUID(), user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups
         INNER JOIN admin_resources ON admin_resources.identifier IN ('lists')
WHERE user_groups.identifier IN ('admin', 'content-editor', 'layout-editor');