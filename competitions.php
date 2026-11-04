<?php
require_once 'config.php';
$q = trim($_GET['q'] ?? '');
render_header('Hackathons');
?>
<section class="page-intro">
    <p class="eyebrow">Team up and build</p>
    <h1>Hackathons</h1>
    <p>Find an event, explore its details, and follow the official link to register.</p>
    <form class="search-form" method="get">
        <label class="sr-only" for="q">Search hackathons</label>
        <input id="q" name="q" value="<?= h($q) ?>" placeholder="Search hackathons">
        <button class="button" type="submit">Search</button>
    </form>
</section>
<div class="card-grid">
    <?php foreach (get_opportunities($db, 'hackathon', $q) as $item) opportunity_card($item, 'hackathon'); ?>
</div>
<?php render_footer(); ?>
