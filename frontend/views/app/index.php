<?php
    use yii\helpers\Url;
    
    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Apps');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Apps');
?>

<div class="row apps_section">
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
		<div class="filters_container">
			<span class="filters_title">
				<?php echo Yii::t('projects', 'filters'); ?>
				<span id="open_div"><i class="fa fa-chevron-down"></i></span>
				<span id="close_div"><i class="fa fa-chevron-up"></i></span>
			</span>
            <div class="accordion">
				<div class="filters all">
					<ul>
			      		<li>
                            <a href="#" data-filter="*" class="resetFilters current" title="Vedi tutti">Tutte le categorie</a>
                        </li>
                    </ul>
			    </div>
				<div class="filters statuses">
					<p>Piattaforma:</p>
					<ul>
						<li>
                            <a id="active" href="#" data-filter=".telegram" class="" title="Filtra per stato">Telegram</a>
                        </li>
                        <li>
                            <a id="" href="#" data-filter=".facebook" class="" title="Filtra per stato">Facebook</a>
                        </li>
                    </ul>
			    </div>
				<div class="filters domains">
					<p>Settore:</p>
					<ul>
						<li>
                            <a href="#" data-filter=".domain__activeCitizenshipCrowdsourcing" class="">Cittadinanza attiva/Crowdsourcing</a>
                        </li>
                        <li>
                            <a href="#" data-filter=".domain__newsMediaAdvertisement" class="" title="Filtra per settore">News, media ed intrattenimento</a>
                        </li>
                    </ul>
			    </div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
		<div class="appsContainer">
            <a href="<?php echo Url::to(['/app/details', 'id' => 1]); ?>" class="app">
                <h2 style="align-self: flex-start">nome app1</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <div class="image_container" style="align-self: flex-end">
                    <img src="https://telegram.org/img/t_logo.png" alt="">
                </div>
            </a>
            <a href="#" class="app">
                <h2>nome app2</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="image_container" style="align-self: flex-end">
                    <img src="https://telegram.org/img/t_logo.png" alt="">
                </div>
            </a>
            <a href="#" class="app">
                <h2>nome app3</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <div class="image_container" style="align-self: flex-end">
                    <img src="https://telegram.org/img/t_logo.png" alt="">
                </div>
            </a>
        </div>
	</div>
</div>
