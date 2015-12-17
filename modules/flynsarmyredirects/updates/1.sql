CREATE TABLE flynsarmyredirects_groups (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) DEFAULT NULL,
    short_description varchar(255) DEFAULT NULL,
    sort_order int(11) DEFAULT NULL,
    created_user_id int(11) DEFAULT NULL,
    updated_user_id int(11) DEFAULT NULL,
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

CREATE TABLE flynsarmyredirects_redirects (
    id int(11) NOT NULL AUTO_INCREMENT,
    group_id int(11) DEFAULT NULL,
    match_type varchar(255) DEFAULT NULL,
    from_url mediumtext DEFAULT NULL,
    to_url mediumtext DEFAULT NULL,
    redirect_code int(11) DEFAULT NULL,
    hits int(11) DEFAULT NULL,
    sort_order int(11) DEFAULT NULL,
    PRIMARY KEY (id),
    KEY group_id_idx (group_id)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

CREATE TABLE flynsarmyredirects_logs (
    id int(11) NOT NULL AUTO_INCREMENT,
    from_url mediumtext DEFAULT NULL,
    to_url mediumtext DEFAULT NULL,
    referrer mediumtext DEFAULT NULL,
    ip varchar(100) DEFAULT NULL,
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;