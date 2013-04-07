<?php
$sql = "CREATE  TABLE IF NOT EXISTS $wpdb->prefix.`jm_transactions` (
  `ID` MEDIUMINT NOT NULL AUTO_INCREMENT ,
  `user_id` MEDIUMINT NOT NULL ,
  `package_id` MEDIUMINT NULL ,
  `post_id` MEDIUMINT NULL ,
  `datetime` BIGINT NOT NULL ,
  `data` LONGTEXT NOT NULL ,
  PRIMARY KEY (`ID`) ,
  INDEX `user_idx` (`user_id` ASC) ,
  INDEX `post_idx` (`post_id` ASC) ,
  INDEX `package_idx` (`package_id` ASC) ,
  CONSTRAINT `user`
    FOREIGN KEY (`user_id` )
    REFERENCES $wpdb->prefix.`users` (`ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `post`
    FOREIGN KEY (`post_id` )
    REFERENCES $wpdb->prefix.`posts` (`ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `package`
    FOREIGN KEY (`package_id` )
    REFERENCES $wpdb->prefix.`jm_packages` (`ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);


?>