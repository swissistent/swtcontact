<?php if(!$in_swtcontact) exit; ?>
			<div class="wrap">
				<?php screen_icon() ?>
				<h2><?php _e('Swissistent Tasks Contact Form', 'swtcontact') ?></h2>
				<div class="metabox-holder meta-box-sortables ui-sortable pointer">
					<div class="postbox" style="float:left;width:30em;margin-right:20px;width:500px;">
						<h3 class="hndle"><span><?php _e('Swissistent Tasks Contact Form Settings', 'swtcontact') ?></span></h3>
						<div class="inside" style="padding: 0 10px">
							<p style="text-align:center"><a href="http://www.swissistent.ch/" title="Swissistent"><img src="<?php echo $plugin_dir; ?>swissistent.png" alt="Swissistent Logo" /></a></p>
							<form method="post" action="options.php">
								<?php settings_fields('swtcontact'); ?>
								<p>
									<label for="username"><?php echo __('Benutzername:', 'username') ?></label><br />
									<input type="text" name="username" value="<?php echo get_option('username'); ?>" style="width:100%" />
                                    <label for="password"><?php echo __('Passwort:', 'password') ?></label><br />
                                    <input type="password" name="password" value="<?php echo get_option('password'); ?>" style="width:100%" />
								</p>
                                <p>
                                    <label for="group"><?php echo __('Kontaktgruppe:', 'group') ?></label><br />
                                    <select name="group" style="width:100%" >
                                       <?php
                                           if (get_option('group_selection'))
                                            {
                                                foreach (get_option('group_selection') as $groupselection)
                                                {
                                                    foreach ($groupselection as $key => $value) {
                                                        if ($key=="groupdisplayname")
                                                        {
                                                            if ($value == get_option('group'))
                                                                echo "<option selected>".$value."</option>";
                                                            else
                                                                echo "<option>".$value."</option>";
                                                        }
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>
                                </p>
                                <p class="submit">
                                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                                </p>
							</form>

							<p style="color:#999239;background-color:#ffffe0;font-size:smaller;padding:0.4em 0.6em !important;border:1px solid #e6db55;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px">Sie haben keinen Swissistent Benutzernamen? Registrieren Sie sich auf <a href="http://www.swissistent.ch/" target="_blank">swissistent.ch</a></p>
						</div>
					</div>									
				</div>
			</div>
