<?php if(!$in_swtcontact) exit; ?>

<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/components/core-min.js"></script>
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/components/enc-base64-min.js"></script>

<script language="javascript">
function encrypt()
{
    str = "Rz92"+document.login.username.value.toLowerCase()+"qv12"+document.login.password.value+"dw56";
    str = CryptoJS.SHA1(str);
    str = CryptoJS.enc.Base64.stringify(str);
    document.login.passwordHash.value=str;
    document.login.password.value="";
    return true;
    
}
</script>

			<div class="wrap">
				<?php screen_icon() ?>
				<h2><?php _e('Swissistent Tasks Contact Form', 'swtcontact') ?></h2>
				<div class="metabox-holder meta-box-sortables ui-sortable pointer">
					<div class="postbox" style="float:left;width:30em;margin-right:20px;width:500px;">
						<h3 class="hndle"><span><?php _e('Swissistent Tasks Contact Form Settings', 'swtcontact') ?></span></h3>
						<div class="inside" style="padding: 0 10px">
							<p style="text-align:center"><a href="http://www.swissistent.ch/" title="Swissistent"><img src="<?php echo $plugin_dir; ?>swissistent.png" alt="Swissistent Logo" /></a></p>

							<form name="login" onsubmit="return encrypt()" method="post" action="options.php">
								<?php   settings_fields('swtcontact'); ?>
								<p>
									<label for="username"><?php echo __('Benutzername:', 'username') ?></label><br />
									<input type="text" name="username" value="<?php echo get_option('username'); ?>" style="width:100%" />
                                    <label for="password"><?php echo __('Passwort:', 'password') ?></label><br />
                                    <input type="password" name="password" style="width:100%" />
                                    <input type="hidden" name="passwordHash" style="width:100%" />
                                    <label for="ignorePattern"><?php echo __('Ignorieren:', 'ignorePattern') ?></label><br />
                                    <input type="text" name="ignorePattern" value="<?php echo get_option('ignorePattern'); ?>" style="width:100%" />
                                </p>
                                <p>
                                    <label for="group"><?php echo __('Kontaktgruppe:', 'group') ?></label><br />
                                    <select name="group" style="width:100%" >
                                       <?php
                                           if (get_option('group_selection'))
                                            {
                                                foreach (get_option('group_selection') as $groupselection)
                                                {
                                                    echo '<option';
                                                    
                                                    if ($groupselection->groupdisplayname==get_option('group'))
                                                        echo ' selected';
                                                
                                                    echo '>'.$groupselection->groupdisplayname.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <label for="project"><?php echo __('Projekt:', 'project') ?></label><br />
                                    <select name="project" style="width:100%" >
                                    <?php
                                        if (get_option('project_selection'))
                                        {
                                            foreach (get_option('project_selection') as $projectselection)
                                            {
                                                echo '<option value="'.$projectselection->projectid.'"';
                                                
                                                if ($projectselection->projectid==get_option('project'))
                                                    echo ' selected';
                                                
                                                echo '>'.$projectselection->projectname.'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                                <p>
                                    <label for="category"><?php echo __('Kategorie:', 'category') ?></label><br />
                                    <select name="category" style="width:100%" >
                                    <?php
                                        if (get_option('category_selection'))
                                        {
                                            foreach (get_option('category_selection') as $categoryselection)
                                            {
                                                echo '<option';
                                                
                                                if ($categoryselection->categoryname==get_option('category'))
                                                    echo ' selected';
            
                                                echo '>'.$categoryselection->categoryname.'</option>';
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
