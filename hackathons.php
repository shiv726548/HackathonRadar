<?php
require_once 'config.php';

$q = trim($_GET['q'] ?? '');
render_header('Find opportunities');
?>
<section class="hero">
    <p class="eyebrow">Find your next opportunity</p>
    <h1>Make your next big idea happen.</h1>
    <p>Explore student hackathons and competitions in one place.</p>
    <form class="search-form" action="index.php" method="get">
        <label class="sr-only" for="q">Search opportunities</label>
        <input id="q" name="q" value="<?= h($q) ?>" placeholder="Search events, organizers, or topics">
        <button class="button" type="submit">Search</button>
    </form>
</section>

<?php if ($q !== ''): ?>
    <section class="section">
        <h2>Search results for “<?= h($q) ?>”</h2>
        <h3>Hackathons</h3>
        <div class="card-grid">
            <?php foreach (get_opportunities($db, 'hackathon', $q) as $item) opportunity_card($item, 'hackathon'); ?>
        </div>
        <h3>Competitions</h3>
        <div class="card-grid">
            <?php foreach (get_opportunities($db, 'competition', $q) as $item) opportunity_card($item, 'competition'); ?>
        </div>
    </section>
<?php else: ?>
    <section class="section">
        <div class="section-heading">
            <div><p class="eyebrow">Build with a team</p><h2>Featured hackathons</h2></div>
            <a href="hackathons.php">Explore all hackathons →</a>
        </div>
        <div class="card-grid">
            <?php foreach (get_opportunities($db, 'hackathon', '', 3) as $item) opportunity_card($item, 'hackathon'); ?>
        </div>
    </section>
    <section class="section">
        <div class="section-heading">
            <div><p class="eyebrow">Show what you can do</p><h2>Featured competitions</h2></div>
            <a href="competitions.php">Explore all competitions →</a>
        </div>
        <div class="card-grid">
            <?php foreach (get_opportunities($db, 'competition', '', 3) as $item) opportunity_card($item, 'competition'); ?>
        </div>
    </section>
<?php endif; ?>
<?php render_footer(); ?>
