<?php
require_once 'config.php';
$q = trim($_GET['q'] ?? '');
render_header('Competitions');
?>
<section class="page-intro">
    <p class="eyebrow">Put your skills to the test</p>
    <h1>Competitions</h1>
    <p>Browse student challenges and open the official registration page for details.</p>
    <form class="search-form" method="get">
        <label class="sr-only" for="q">Search competitions</label>
        <input id="q" name="q" value="<?= h($q) ?>" placeholder="Search competitions">
        <button class="button" type="submit">Search</button>
    </form>
</section>
<div class="card-grid">
    <?php foreach (get_opportunities($db, 'competition', $q) as $item) opportunity_card($item, 'competition'); ?>
</div>
<?php render_footer(); ?>
