<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Apps');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Apps');

    $platforms = WenetApp::getPlatforms();
	asort($platforms);

    $tags = WenetApp::getTags();
	asort($tags);
?>

<?php if (!WenetApp::thereAreActiveApps()) { ?>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            non ci sono app da mostrare
            <!-- TODO -->
        </div>
    </div>
<?php } else { ?>
    <div class="row apps_section">
    	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
    		<div class="filters_container">
                <span class="filter_title">
					<?php echo Yii::t('app', 'filters'); ?>
					<!-- <span id="open_div"><i class="fa fa-chevron-down"></i></span>
					<span id="close_div"><i class="fa fa-chevron-up"></i></span> -->
				</span>
				<div class="accordion">
                    <div class="filters all">
						<ul>
							<?php
								$currentClass = "";
								if (count($activePlatformsList) == 0 && count($activeTagsList) == 0) {
									$currentClass = "current";
								}
								echo '<li><a href="#" data-filter="*" class="resetFilters '.$currentClass.'" title="'.Yii::t('title', 'See all').'">'.Yii::t('app', 'all').'</a></li>';
							?>
				  		</ul>
				    </div>
                    <div class="filters platforms">
						<p><?php echo Yii::t('app', 'filter_platform'); ?>:</p>
						<ul>
							<?php foreach ($platforms as $key => $pp) {
								$currentClass = "";
								$currentTag = 'platform__'.$pp;
								if (in_array($currentTag, $activePlatformsList)) {
									$currentClass = "current";
								}
								echo '<li><a id="'.$key.'" href="#" data-filter=".'.$currentTag.'" class="'.$currentClass.'" title="'.Yii::t('title', 'Filter for platform').'">'.WenetApp::label($pp).'</a></li>';
							} ?>
				  		</ul>
				    </div>
                    <div class="filters tags">
						<p><?php echo Yii::t('app', 'filter_tags'); ?>:</p>
						<ul>
							<?php foreach ($tags as $key => $tt) {
								$currentClass = "";
								$currentTag = 'tag__'.$tt;
								if (in_array($currentTag, $activeTagsList)) {
									$currentClass = "current";
								}
								echo '<li><a id="'.$key.'" href="#" data-filter=".'.$currentTag.'" class="'.$currentClass.'" title="'.Yii::t('title', 'Filter for tag').'">'.WenetApp::label($tt).'</a></li>';
							} ?>
				  		</ul>
				    </div>
			    </div>
		    </div>
	    </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
    		<div class="appsContainer">
                <?php
                    $apps = WenetApp::activeApps();

                    foreach ($apps as $key => $app) {
                        $itemTags = [];
						foreach ($app->associatedCategories as $category) {
							if (in_array($category, $tags)) {
								$itemTags[] = 'tag__' . $category;
							}
						}

                        $availablePlatforms = [];
                        $availablePlatforms = ["telegram"]; //TODO
                        $platformsContent = '<ul>';
                        foreach ($availablePlatforms as $key => $ap) {
                            $platformsContent .= '<li>';
                                $platformsContent .= '<div class="image_container" style="align-self: flex-end">';
                                    $platformsContent .= '<img src="https://telegram.org/img/t_logo.png" alt="'. Yii::t('title', 'platform icon') .'">';
                                $platformsContent .= '</div>';
                            $platformsContent .= '</li>';
                        }
                        $platformsContent .= '</ul>';

                        $content = '<a href="'. Url::to(['/wenetapp/details', 'id' => $app->id]) .'" class="'.implode($itemTags, ' ').' app">';
                            $content .= '<h2>'. $app->name .'</h2>';
                            $content .= '<p>'. $app->description .'</p>';
                            $content .= $platformsContent;
                        $content .= '</a>';

                        echo $content;
                    }

                ?>
            </div>
    	</div>
	</div>
<?php } ?>

<script type="text/javascript">

var platforms = <?php echo json_encode($activePlatformsList); ?>;
var tags = <?php echo json_encode($activeTagsList); ?>;
var displayedResourceIds = [];

function getResourceIdForPreview(element) {
	var elementClasses = element.attr("class").split(" ");

	var elementResourceId = undefined;
	for (var elementClassIndex in elementClasses) {
		var elementClass = elementClasses[elementClassIndex];
		if (elementClass.includes("resourcePreview__")) {
			elementResourceId = elementClass.replace("resourcePreview__", "");
		}
	}
	return elementResourceId;
}

function filterFunction( itemElem ) {

	var element = $(this);

	var elementResourceId = getResourceIdForPreview(element);
	if (elementResourceId === undefined) {
		return false;
	}

	if (filtersEmpty()) {
		displayedResourceIds.push(elementResourceId);
		return true;
	} else {
		var platformBool = true;
		if (platforms.length > 0) {
			platformBool = false;
			for (var i = 0; i < platforms.length; i++) {
				var platformToCheck = platforms[i];
				platformBool = platformBool || element.hasClass(platformToCheck.replace(".", ""));
			}
		}

		var tagBool = true;
		if (tags.length > 0) {
			tagBool = false;
			for (var i = 0; i < tags.length; i++) {
				var tagToCheck = tags[i];
				tagBool = tagBool || $(this).hasClass(tagToCheck.replace(".", ""));
			}
		}

		var shoudldBeDisplayed = platformBool && tagBool;
		if (shoudldBeDisplayed) {
			displayedResourceIds.push(elementResourceId);
		}
		return shoudldBeDisplayed;
	}
}

var $container = $('.appsContainer');
function updateBoxView() {
	$container.isotope({
        filter: filterFunction,
        animationOptions: {
            duration: 750,
            easing: 'linear',
            queue: false
        }
    });
}

$(window).on('load', function(){
	displayedResourceIds = [];
	updateBoxView();

    $('.filters a').click(function(){
		var selector = $(this).attr('data-filter');
		var selectorType = getSelectorType(selector);

		if($(this).hasClass('resetFilters')){
			console.log('resetting filters');
			$('.platforms a').removeClass('current');
			$('.tags a').removeClass('current');
			$(this).addClass('current');
			resetFitlers();
		} else {
			if ($(this).hasClass('current')) {
				console.log('removing filter ' + selector);
				$('.resetFilters').removeClass('current');
				$(this).removeClass('current');
				removeFilter(selector, selectorType);
				if (filtersEmpty()) {
					$('.resetFilters').addClass('current');
					resetFitlers();
				}
			} else {
				console.log('applying filter ' + selector);
				$('.sectors a.allCategories').removeClass('current');
				$('.resetFilters').removeClass('current');
				$(this).addClass('current');
				addFilter( selector, selectorType );
				console.log(selector +", "+ selectorType);
			}
		}

		displayedResourceIds = [];
		updateBoxView();
    });

	$('span.filter_title').click(function(){
		if($(document).innerWidth() < 768){
			if($('div.accordion').hasClass('open')){
				$('div.accordion').css('height', '0px');
				$('div.accordion').removeClass('open');
				$('.filters_container span.filter_title span#open_div').css('display', 'block');
				$('.filters_container span.filter_title span#close_div').css('display', 'none');
			} else {
				$('div.accordion').css('height', 'auto');
				$('div.accordion').addClass('open');
				$('.filters_container span.filter_title span#open_div').css('display', 'none');
				$('.filters_container span.filter_title span#close_div').css('display', 'block');
			}
		} else {
			$('div.accordion').removeClass('open');
			$('div.accordion').css('height', 'auto');
			$('.filters_container span.filter_title span').css('display', 'none');
		}
	});

});

$(window).resize(function() {
	var pageWidth = $(document).innerWidth();

	if(pageWidth > 768){
		$('.filters_container span.filter_title span').css('display', 'none');
	} else {
		$('.filters_container span.filter_title span#open_div').css('display', 'block');
	}
});

function resetFitlers() {
	platforms = [];
	tags = [];
}

function getSelectorType( selector ) {
	if (selector.includes("tag__")) {
		return "tag";
	} else {
		return "platform";
	}
}

function addFilter( filter, filterType ) {
	if (filterType == "tag") {
		if ( tags.indexOf( filter ) == -1 ) {
			tags.push( filter );
		}
	} else if (filterType == "platform") {
		if ( platforms.indexOf( filter ) == -1 ) {
			platforms.push( filter );
		}
	}
}

function removeFilter( filter, filterType ) {
	if (filterType == "tag") {
		var index = tags.indexOf( filter);
		if ( index != -1 ) {
			tags.splice( index, 1 );
		}
	} else if (filterType == "platform") {
		var index = platforms.indexOf( filter);
		if ( index != -1 ) {
			platforms.splice( index, 1 );
		}
	}
}

function filtersEmpty() {
	return platforms.length == 0 && tags.length == 0;
}

</script>
