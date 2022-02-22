<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Apps');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Apps');

    $platforms = WenetApp::getSourceLinks();
    asort($platforms);

    $tags = WenetApp::getTags();
    asort($tags);
?>

<?php if (!WenetApp::thereAreActiveApps()) { ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p style="text-align:center; font-weight:400;">
                <?php echo Yii::t('app', 'There are no apps to display.'); ?>
            </p>
        </div>
    </div>
<?php } else { ?>
    <div class="row apps_section">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="filters_container">
                <span class="filter_title">
                    <?php echo Yii::t('app', 'filters'); ?>
                    <span id="open_div"><i class="fa fa-chevron-down"></i></span>
                    <span id="close_div"><i class="fa fa-chevron-up"></i></span>
                </span>
                <div class="accordion">
                    <div class="filters all">
                        <ul>
                            <?php
                            $currentClass = "";
                            if (count($activePlatformsList) == 0 && count($activeTagsList) == 0) {
                                $currentClass = "current";
                            }
                            echo '<li><a href="#" data-filter="*" class="resetFilters '.$currentClass.'" title="'.Yii::t('app', 'See all').'">'.Yii::t('app', 'all').'</a></li>';
                            ?>
                        </ul>
                    </div>
                    <div class="filters platforms">
                        <p><?php echo Yii::t('app', 'filter_platform'); ?>:</p>
                        <ul>
                            <?php
                            foreach ($platforms as $key => $pp) {
                                $currentClass = "";
                                $currentTag = 'platform__'.$pp;
                                if (in_array($currentTag, $activePlatformsList)) {
                                    $currentClass = "current";
                                }
                                echo '<li><a id="'.$key.'" href="#" data-filter=".'.$currentTag.'" class="'.$currentClass.'" title="'.Yii::t('app', 'Filter for platform').'">'.WenetApp::sourceLinksLabel($pp).'</a></li>';
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
                                echo '<li><a id="'.$key.'" href="#" data-filter=".'.$currentTag.'" class="'.$currentClass.'" title="'.Yii::t('app', 'Filter for tag').'">'.WenetApp::tagLabel($tt).'</a></li>';
                            } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
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

                        $activeSourceLinks = '';
                        if($app->hasActiveSourceLinksForApp()){
                            $activeSourceLinks .= '<ul class="source_links_list table_view">' . implode(array_map(function($sl){
                                return '<li><img src="'.Url::base().'/images/platforms/'.$sl.'.png" alt="'.$sl." ". Yii::t('app', 'Source link image').'"></li>';
                            }, $app->getActiveSourceLinksForApp()), '') . '</ul>';
                        }

                        $availablePlatforms = array_map(function($p){
                            return 'platform__'.$p;
                        },$app->getActiveSourceLinksForApp());

                        $content = '<a href="'. Url::to(['/wenetapp/app-details', 'id' => $app->id, 'back' => 'index']) .'" class="'.implode($itemTags, ' '). ' '.implode($availablePlatforms, ' ').' app appId__'.$app->id.'">';

                        if($app->image_url != null){
                            $content .= '<div class="app_icon_image big_icon" style="margin-top:0px !important; background-image: url('.$app->image_url.')"></div>';
                        } else {
                            $content .= '<div class="app_icon big_icon"><span>'.strtoupper($app->name[0]).'</span></div>';
                        }

                        $description = nl2br($app->description);
                        if(strlen($description) > 150){
                            $description = substr($description, 0, 150) . '...';
                        }

                        $content .= '<h2 dir="auto">'. $app->name .'</h2>';
                        $content .= '<p dir="auto">'. $description .'</p>';
                        $content .= $activeSourceLinks;
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
var displayedAppIds = [];

function getAppIdForPreview(element) {
    var elementClasses = element.attr("class").split(" ");

    var elementAppId = undefined;
    for (var elementClassIndex in elementClasses) {
        var elementClass = elementClasses[elementClassIndex];
        if (elementClass.includes("appId__")) {
            elementAppId = elementClass.replace("appId__", "");
        }
    }
    return elementAppId;
}

function filterFunction( itemElem ) {
    var element = $(this);

    var elementAppId = getAppIdForPreview(element);
    if (elementAppId === undefined) {
        return false;
    }

    if (filtersEmpty()) {
        displayedAppIds.push(elementAppId);
        return true;
    } else {
        var platformsBool = true;
        if (platforms.length > 0) {
            platformsBool = false;
            for (var i = 0; i < platforms.length; i++) {
                var platformsToCheck = platforms[i];
                platformsBool = platformsBool || element.hasClass(platformsToCheck.replace(".", ""));
            }
        }

        var tagsBool = true;
        if (tags.length > 0) {
            tagsBool = false;
            for (var i = 0; i < tags.length; i++) {
                var tagToCheck = tags[i];
                tagsBool = tagsBool || $(this).hasClass(tagToCheck.replace(".", ""));
            }
        }

        var shouldBeDisplayed = platformsBool && tagsBool;
        if (shouldBeDisplayed) {
            displayedAppIds.push(elementAppId);
        }
        return shouldBeDisplayed;
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
    displayedAppIds = [];
    updateBoxView();

    $('.filters a').click(function(){
        var selector = $(this).attr('data-filter');
        var selectorType = getSelectorType(selector);

        if($(this).hasClass('resetFilters')){
            // console.log('resetting filters');
            $('.platforms a').removeClass('current');
            $('.tags a').removeClass('current');
            $(this).addClass('current');
            resetFitlers();
        } else {
            if ($(this).hasClass('current')) {
                // console.log('removing filter ' + selector);
                $('.resetFilters').removeClass('current');
                $(this).removeClass('current');
                removeFilter(selector, selectorType);
                if (filtersEmpty()) {
                    $('.resetFilters').addClass('current');
                    resetFitlers();
                }
            } else {
                // console.log('applying filter ' + selector);
                $('.sectors a.allCategories').removeClass('current');
                $('.resetFilters').removeClass('current');
                $(this).addClass('current');
                addFilter( selector, selectorType );
                // console.log(selector +", "+ selectorType);
            }
        }

        displayedAppIds = [];
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
