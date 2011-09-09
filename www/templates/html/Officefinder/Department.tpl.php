<?php

// Set the page title and breadcrumbs
UNL_Officefinder::setReplacementData('doctitle', 'UNL | Directory | '.$context->name);
UNL_Officefinder::setReplacementData('breadcrumbs', '
    <ul>
        <li><a href="http://www.unl.edu/" title="University of Nebraska–Lincoln">UNL</a></li>
        <li><a href="'.UNL_Peoplefinder::getURL().'">Directory</a></li>
        <li>'.$context->name.'</li>
    </ul>');

$userCanEdit = false;

// Check if the user can edit and store this result for later
if ($context->options['view'] != 'alphalisting') {
    $userCanEdit = $context->userCanEdit(UNL_Officefinder::getUser());
}
?>
<div class="departmentInfo">
    <?php
    $image_url = 'http://maps.unl.edu/images/building/icon_md.png';
    if (!empty($context->building)) {
        $bldgs = new UNL_Common_Building();
        if ($bldgs->buildingExists($context->building)) {
            $image_url = 'http://maps.unl.edu/'.urlencode($context->building).'/image';
        }
    }
    ?>
    <div id="departmentDisplay" class="vcard office">
        <img alt="Building Image" src="<?php echo $image_url; ?>" width="100" height="100" class="frame photo">
        <h2 class="fn org">
            <?php
            echo $context->name;
            if (!empty($context->org_unit)) {
                echo ' <span class="unl-hr-org-unit-number">('.$context->org_unit.')</span>';
            }
            if ($userCanEdit) {
                echo '<ul class="edit_actions">';
                    echo '<li><a href="'.$context->getURL().'?format=editing" class="action edit" title="Edit">Edit</a></li>';
                    if (!isset($context->org_unit) || UNL_Officefinder::isAdmin(UNL_Officefinder::getUser(true))) {
                        // Only allow Admins to delete "official" SAP departments
                        echo '<li>';
                        include dirname(__FILE__).'/../../editing/Officefinder/Department/DeleteForm.tpl.php';
                        echo '</li>';
                    }
                echo '</ul>';
            }
            ?>
        </h2>
        <div class="vcardInfo">
            <div class="adr label">
                <span class="room"><?php echo $context->room.' <a class="location mapurl" href="http://maps.unl.edu/#'.$context->building.'">'.$context->building.'</a>'; ?></span>
                <?php
                if (!empty($context->address)) {
                    echo "<span class='street-address'>" . $context->address . "</span>";
                }
                if (!empty($context->city)) {
                    echo "<span class='locality'>" . $context->city . "</span>";
                }
                if (!empty($context->state)) {
                    echo "<span class='region'>" . $context->state . "</span>";
                }
                if (!empty($context->postal_code)) {
                    echo "<span class='postal-code'>" . $context->postal_code . "</span>";
                }
                ?>
                <span class='country-name'>USA</span>
            </div>
            
            <?php if (isset($context->phone)): ?>
            <div class="tel">
                <span class="voice">Phone:
                    <?php
                    echo $savvy->render($context->phone, 'Peoplefinder/Record/TelephoneNumber.tpl.php');
                    ?>
                </span>
            </div>
            <?php endif; ?>
            <?php if (isset($context->fax)): ?>
            <div class="tel">
                <span class="fax">Fax:
                    <?php
                    echo $savvy->render($context->fax, 'Peoplefinder/Record/TelephoneNumber.tpl.php');
                    ?>
                </span>
            </div>
            <?php endif; ?>
            
            
            <?php if (isset($context->email)): ?>
            <div class="email">
                <span class="email">
                   <a class="email" href="mailto:<?php echo $context->email; ?>"><?php echo $context->email; ?></a>
                </span>
            </div>
            <?php endif; ?>
            <?php if (isset($context->website)): ?>
            <div class="url">
                <span class="url">
                   <a class="url" href="<?php echo $context->website; ?>"><?php echo $context->website; ?></a>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    if ($userCanEdit) {
        echo $savvy->render($context, 'Officefinder/Department/EditBox.tpl.php');
    }

    // Get the official org unit if possible
    $department = $context->getHRDepartment();

    ?>
</div>
<div class="clear"></div>
<div class="grid8 first">
    <ul class="wdn_tabs">
        <li><a href="#listings">Listings</a></li>
        <?php if ($department && count($department) > 0): ?>
        <li><a href="#all_employees">All Employees <sup><?php echo count($department); ?></sup></a></li>
        <?php endif; ?>
    </ul>
    <div class="wdn_tabs_content">
        <div id="listings">
        <?php
        $listings = $context->getUnofficialChildDepartments();
        if (count($listings)) {
            echo $savvy->render($listings, 'Officefinder/Department/Listings.tpl.php');
        }
        if ($userCanEdit) {
            echo '<a href="'.UNL_Officefinder::getURL(null, array('view'      => 'department',
                                                                  'parent_id' => $context->id)).'&amp;format=editing">Add a new child-listing</a>';
        }
        ?>
        </div>
        <?php
        if ($department && count($department) > 0) {
            // This listing has an official HR department associated with IT
            // render all those HR department details.
            echo $savvy->render($department);
        }
        
        ?>
    </div>
</div>
<div class="grid4" id="orgChart">
<h2>HR Organization Chart Position</h2>
<?php
if (!$context->isRoot()) {
    $parent = $context->getParent();
    echo '<ul>
            <li><a href="'.$parent->getURL().'">'.$parent->name.'</a>';
}
?>

            <ul>
                <li><?php echo $context->name; ?>
                    <?php if ($context->hasOfficialChildDepartments()): ?>
                    <ul>
                        <?php foreach ($context->getOfficialChildDepartments('name ASC') as $child): ?>
                        <li><a href="<?php echo $child->getURL(); ?>"><?php echo $child->name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
            </ul>
<?php
if (!$context->isRoot()) {
        echo '</li>
    </ul>';
}
?>
</div>
<a id="reportProblem" class="dir_correctionRequest noprint" href="http://www1.unl.edu/comments/">Have a correction?</a>
