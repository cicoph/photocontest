<div id="hybridauth-openid-div">
	<p>Enter your OpenID identity or provider:</p>
	<form action="<?php echo $this->config['baseUrl'];?>/default/login/" method="get" id="hybridauth-openid-form" >
		<input type="hidden" name="provider" value="openid"/>
		<input type="text" name="openid-identity" size="30"/>
	</form>
</div>

<div id="hybridauth-confirmunlink">
	<p>Unlink provider?</p>
	<form action="<?php echo $this->config['baseUrl'];?>/default/unlink" method="post" id="hybridauth-unlink-form" >
		<input type="hidden" name="hybridauth-unlinkprovider" id="hybridauth-unlinkprovider" value=""/>
	</form>
</div>

<?php foreach (Yii::app()->user->getFlashes() as $key => $message): ?>
	<div class="flash-error"> <?php echo $message ?> </div>
<?php endforeach; ?>		

<ul class='hybridauth-providerlist'>
	<?php foreach ($providers as $provider => $settings): ?>
		<?php if($settings['enabled'] == true): ?> 
			 <?php if (($settings['active']==true) && (!Yii::app()->user->isGuest)): ?> 					
					<li class='active'> <div id=<?php echo $provider ?>" class="zocial <?php echo $provider ?>">Remove <?php echo $provider ?></div>
				<?php else: ?>
					<li class='inactive'><div id="<?php echo $provider ?>" class="zocial <?php echo $provider ?>">Sing - In with <?php echo $provider ?></div>
				<?php endif; ?>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
<script>
$('li.inactive div').click(function(){
		var provider = $(this).attr('id');
		start_auth( provider );
});
function start_auth( params ){
			var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
                 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
                 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
                 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
                 width    = 800,
                 height   = 500,
                 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
                 top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                 features = (
                    'width=' + width +
                    ',height=' + height +
                    ',left=' + left +
                    ',top=' + top
                  );
			var start_url = "<?php echo $baseUrl?>/default/login/?provider="+ params +"&return_to=<?php echo $this->config['baseUrl']; ?>" + "&_ts=" + (new Date()).getTime();
			signinWin = window.open(
				start_url, 
				"Login With Facebook", 
				features
			);
			
			return false;
		}
</script>