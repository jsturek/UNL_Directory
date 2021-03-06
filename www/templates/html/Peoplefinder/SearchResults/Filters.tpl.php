<div id="filters">
  <nav>
    <a class="dcf-show-on-focus" href="#results">Skip filters</a>
  </nav>
  <h2 class="dcf-txt-h4">Filter Results</h2>
  <div class="filters" aria-controls="results">
    <div class="affiliation">
      <button class="dcf-btn dcf-d-block dcf-txt-left dcf-mb-4 dcf-p-0 dcf-b-0 dcf-bg-transparent unl-darker-gray" aria-controls="filters_affiliation">By Affiliation <span class="toggle">(Expand)</span></button>
      <div class="filter-options dcf-mb-7 dcf-txt-xs" id="filters_affiliation" role="region" tabindex="-1" aria-expanded="false" ></div>
    </div>
    <div class="department">
      <button class="dcf-btn dcf-d-block dcf-txt-left dcf-mb-4 dcf-p-0 dcf-b-0 dcf-bg-transparent unl-darker-gray" aria-controls="filters_department">By Department <span class="toggle">(Expand)</span></button>
      <div class="filter-options dcf-mb-7 dcf-txt-xs" id="filters_department" role="region" tabindex="-1" aria-expanded="false" ></div>
    </div>
  </div>
</div>

<?php echo $savvy->render((object) [
    'id' => 'filterOptionListTempalte',
    'template' => 'SearchResults/OptionListTemplate.tpl.php',
], 'jsrender.tpl.php') ?>

<?php echo $savvy->render((object) [
    'id' => 'filterOptionTemplate',
    'template' => 'SearchResults/OptionTemplate.tpl.php',
], 'jsrender.tpl.php') ?>

<?php echo $savvy->render((object) [
    'id' => 'summaryTemplate',
    'template' => 'SearchResults/SummaryTemplate.tpl.php',
], 'jsrender.tpl.php') ?>

<?php echo $savvy->render((object) [
    'id' => 'summaryAllTemplate',
    'template' => 'SearchResults/SummaryAllTemplate.tpl.php',
], 'jsrender.tpl.php') ?>

<?php echo $savvy->render((object) [
    'id' => 'summaryFilterTemplate',
    'template' => 'SearchResults/SummaryFilterTemplate.tpl.php',
], 'jsrender.tpl.php') ?>
