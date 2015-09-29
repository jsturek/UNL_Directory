<?php
$preferredFirstName = $context->getPreferredFirstName();
$preferredName = $preferredFirstName . ' ' . $context->sn;
$telephonePartial = 'Peoplefinder/Record/TelephoneNumber.tpl.php';

$isOrg = $context->ou == 'org';
$itemtype = 'Person';
if ($isOrg) {
    $itemtype = 'Organization';
}

$displayEmail = false;
$encodedEmail = '';
if (isset($context->mail) && !$context->isPrimarilyStudent()) {
    $displayEmail = true;
    // attempt to curb lazy email harvesting bots
    $encodedEmail = htmlentities($context->getRaw('mail'), ENT_QUOTES | ENT_HTML5);
}

$showKnowledge = $context->shouldShowKnowledge();
?>
<?php if ($showKnowledge): ?>
<section class="wdn-grid-set knowledge-grid">
    <div class="bp2-wdn-col-two-sevenths">
<?php endif; ?>


<div class="vcard <?php if (!$showKnowledge): ?>card <?php endif; ?><?php echo $context->eduPersonPrimaryAffiliation ?>" itemscope itemtype="http://schema.org/<?php echo $itemtype ?>">
    <a class="card-profile" href="<?php echo $context->getProfileUrl() ?>" title="PlanetRed profile for <?php echo $preferredName ?>"><img class="photo" src="<?php echo $context->getImageURL(UNL_Peoplefinder_Record_Avatar::AVATAR_SIZE_LARGE) ?>" alt="Avatar for <?php echo $preferredName ?>" /></a>

    <div class="vcardInfo<?php if (!$showKnowledge): ?> card-content<?php endif; ?>">
    <?php if ($showKnowledge): ?>
        <h1 class="headline">
    <?php else: ?>
        <div class="headline">
    <?php endif; ?>
        <?php if (!$isOrg): ?>
            <a class="permalink" href="<?php echo $context->getUrl() ?>">
        <?php endif; ?>
        <?php if ($isOrg): ?>
            <span class="cn"><?php echo $context->cn ?></span>
        <?php else: ?>
            <span class="fn"><?php echo $preferredName ?></span>
            <span class="n">
            <?php if ($context->hasNickname()): ?>
                <span class="givenName"><?php echo $context->givenName ?></span>
            <?php endif; ?>
            </span>
        <?php endif; ?>
        <?php if (!$isOrg): ?>
            <span class="icon-link"></span></a>
        <?php endif; ?>
    <?php if (!$showKnowledge): ?>
        </div>
    <?php else: ?>
        </h1>
    <?php endif; ?>

    <?php
    $affiliations = $context->formatAffiliations();
    ?>
    <?php if ($affiliations): ?>
        <div class="eppa">(<?php echo $affiliations ?>)</div>
    <?php endif; ?>

    <?php if ($context->hasStudentInformation()): ?>
        <div class="sis-title">
        <?php if (isset($context->unlSISClassLevel)): ?>
            <span class="grade"><?php echo $context->formatClassLevel() ?></span>
        <?php endif; ?>
        <?php if (isset($context->unlSISMajor)): ?>
            <?php foreach ($context->getRawObject()->unlSISMajor as $major): ?>
                <span class="major"><?php echo $context->formatMajor($major) ?></span>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if (isset($context->unlSISMinor)): ?>
            <?php foreach ($context->getRawObject()->unlSISMinor as $minor): ?>
                <span class="minor"><?php echo $context->formatMajor($minor) ?></span>
            <?php endforeach; ?>
        <?php endif; ?>
            <?php foreach ($context->getRawObject()->unlSISCollege as $college): ?>
                <?php
                $college = $context->formatCollege($college);
                ?>
                <?php if (is_string($college)): ?>
                    <span class="icon-building college"><?php echo $college ?></span>
                <?php else: ?>
                    <span class="icon-building college">
                        <?php if (isset($college['link'])): ?>
                            <a href="<?php echo $college['link'] ?>">
                        <?php endif; ?>
                        <abbr title="<?php echo $college['title'] ?>"><?php echo $college['abbr'] ?></abbr>
                        <?php if (isset($college['org_unit_number'])): ?>
                            </a>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($context->unlHROrgUnitNumber)): ?>
        <?php
        $roles = $context->getRoles();
        if (count($roles)) {
            echo $savvy->render($roles);
        }
        ?>
    <?php endif ?>

    <?php if (($address = $context->formatPostalAddress()) && count($address)): ?>
        <div class="adr work itemprop icon-map-pin">
            <span class="type">Work</span>
        <?php if (!empty($address['unlBuildingCode'])): ?>
            <span class="street-address">
                <a href="https://maps.unl.edu/<?php echo $address['unlBuildingCode'] ?>"><?php echo $address['unlBuildingCode'] ?></a>
                <?php echo str_replace($address['unlBuildingCode'], '', $address['street-address']) ?>
            </span>
        <?php else: ?>
            <span class="street-address"><?php echo $address['street-address'] ?></span>
        <?php endif; ?>
             <span class="locality"><?php echo $address['locality'] ?></span>
             <?php echo $savvy->render($address['region'], 'Peoplefinder/Record/Region.tpl.php') ?>
             <span class="postal-code"><?php echo $address['postal-code'] ?></span>
             <div class="country-name">USA</div>
        </div>
    <?php endif; ?>

    <?php if (isset($context->telephoneNumber)): ?>
        <div class="tel work icon-phone itemprop">
            <span class="voice">
                <span class="type">Work</span>
                <span class="value"><?php echo $savvy->render($context->telephoneNumber, $telephonePartial) ?></span>
            </span>
        </div>
    <?php endif; ?>

    <?php if (isset($context->unlSISLocalPhone)): ?>
        <div class="tel home">
            <span class="voice">
                <span class="type">Phone</span>
                <span class="value"><?php echo $savvy->render($context->unlSISLocalPhone, $telephonePartial) ?></span>
            </span>
        </div>
    <?php endif; ?>

    <?php if ($displayEmail): ?>
        <div class="icon-email itemprop">
            <a class="email" href="mailto:<?php echo $encodedEmail ?>" itemprop="email"> <?php echo $encodedEmail ?></a>
        </div>
    <?php endif; ?>
    </div>

    <div class="vcard-tools">
        <a href="<?php echo $context->getVcardUrl() ?>" class="icon-vcard"><span class="wdn-text-hidden">Download </span>vCard<span class="wdn-text-hidden"> for <?php echo $preferredName ?></span></a>
        <a href="<?php echo $context->getQRCodeUrl($savvy->render($context, 'templates/vcard/Peoplefinder/Record.tpl.php')) ?>" class="icon-qr-code">QR Code<span class="wdn-text-hidden"> vCard for <?php echo $preferredName ?></span></a>
        <button class="icon-print">Print<span class="wdn-text-hidden"> listing for <?php echo $preferredName ?></span></a>
        <div title="Leave notes on the listing for <?php echo $preferredName ?>" class="wdn_annotate" id="directory_<?php echo $context->uid ?>"></div>
    </div>
</div>

<?php if ($showKnowledge): ?>
    </div>
    <div class="bp2-wdn-col-five-sevenths">
        <div class="card">
            <div class="card-content">
                <?php echo $savvy->render($context->getKnowledge()) ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>