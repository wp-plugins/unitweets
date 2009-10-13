
<div class="wrap">
	<h2>UniTweets Settings</h2>
	
	<form method="post" action="options.php">

		<?php settings_fields( 'uniTweetsOptions' ); ?>
		<?php $options = get_option( 'uniTweetsSettings' ); ?>

		<h3>General Settings</h3>
	
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Post Tweets in Category</th>
				<td>
  					<select name="uniTweetsSettings[category]">
					
						<?php 
							$currentCat = $options['category'];
							$categories = get_categories( 'hide_empty=0' ); 
							
							foreach ($categories as $cat) :
						?>
							
							<option value="<?php echo $cat->cat_ID; ?>" <?php if( $currentCat == $cat->cat_ID ): ?>selected="selected"<?php endif; ?>><?php echo $cat->cat_name; ?></option>
						
						<?php 
							endforeach; 
						?>
					</select>
				</td>
			</tr>
		</table>	
		
		
		<?php 
			$accounts = get_option( 'uniTweetsAccounts' );
			
			$i = 0;
			
			if ( $accounts ) :
			
				foreach( $accounts as $account ) : 
		?>
			<h3>UniTweets Account #<?php echo $i+1; ?></h3>
			
			<p>
				Leaving either 'username' or 'password' blank will delete the account.
			</p>
		
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Twitter Username</th>
					<td>
						<input class="regular-text" type="text" name="uniTweetsAccounts[<?php echo $i; ?>][user]" value="<?php echo $account['user']; ?>" />
						<span>Required.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Twitter Password</th>
					<td>
						<input class="regular-text" type="password" name="uniTweetsAccounts[<?php echo $i; ?>][pass]" value="<?php echo $account['pass']; ?>" />
						<span>Required.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Tweet Author</th>
					<td>
	  					<?php wp_dropdown_users('selected=' . $account['author'] . '&name=uniTweetsAccounts[' . $i . '][author]' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Hashtag</th>
					<td>
						<input class="regular-text" type="text" name="uniTweetsAccounts[<?php echo $i; ?>][hash]" value="<?php echo $account['hash']; ?>" />
						<span>(ex. #unitinteractive) This is an optional filter. If 'hashtag' is set, only tweets containing the exact text will be saved to your blog.</span>
					</td>
				</tr>
			</table>
				
		<?php 
					$i++;	
				endforeach; 
			endif;
		?>
			
		<h3>New UniTweets Account</h3>
	
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Twitter Username</th>
				<td>
					<input class="regular-text" type="text" name="uniTweetsAccounts[<?php echo $i; ?>][user]" />
					<span>Required.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Twitter Password</th>
				<td>
					<input class="regular-text" type="password" name="uniTweetsAccounts[<?php echo $i; ?>][pass]" />
					<span>Required.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Tweet Author</th>
				<td>
  					<?php wp_dropdown_users( 'name=uniTweetsAccounts[' . $i .'][author]' ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Hashtag</th>
				<td>
					<input class="regular-text" type="text" name="uniTweetsAccounts[<?php echo $i; ?>][hash]" />
					<span>(ex. #unitinteractive) This is an optional filter. If 'hashtag' is set, only tweets containing the exact text will be saved to your blog.</span>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
		