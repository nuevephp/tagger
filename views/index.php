<h1><?php echo __('Tagger'); ?></h1>

<div id="snippets-def" class="index-def">
  <div class="snippet"><?php echo __('Tags'); ?></div>
</div>

<ul id="snippets" class="index">
<?php foreach($tags as $tag): ?>
  <li id="snippet_<?php echo $tag->id; ?>" class="snippet node <?php echo odd_even(); ?>">
    <img align="middle" alt="snippet-icon" src="<?php echo TAGGER_URL; ?>/images/tag.png" />
    <a href="<?php echo get_url('plugin/tagger/edit/'.$tag->id); ?>"><?php echo $tag->name; ?>  (<?php echo $tag->count; ?>)</a>
    <?php
    $tagCount = count(TaggerTag::findTagsAndPageAssigned($tag->id));
    if($tagCount > 0): $n = 0; ?>
    <ul class="tagged">
      <?php echo __('tagged:'); ?>
      <?php foreach(TaggerTag::findTagsAndPageAssigned($tag->id) as $page_id => $title): $n++ ?>
		<li id="<?php echo $page_id; ?>">
			<a href="<?php echo get_url('page/edit/'.$page_id); ?>"><?php echo $title; ?></a>
			<a href="<?php echo get_url('plugin/tagger/endrelationship/' . $page_id . '-' . $tag->id); ?>" style="padding: 0 2px;">[&times;]</a><?php if($tagCount != 1 && $n != $tagCount) echo '&sbquo; '; ?>
		</li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div class="remove"><a href="<?php echo get_url('plugin/tagger/delete/'.$tag->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete'); ?> <?php echo $tag->name; ?>?');"><img src="<?php echo TAGGER_URL; ?>/images/icon-remove.gif" alt="remove icon" /></a></div>
  </li>
<?php endforeach; ?>
</ul>

<div class="pagination">
<?php
  if ($currentpage == $lastpage) {
    $next = '<span class="disabled">Next Page</span>';
  } else {
    $nextpage = $currentpage + 1;
    $next = '<a href="' . get_url('plugin/tagger/index/') . '' . $nextpage .
      '">Next Page</a>';

  }
  if ($currentpage == 0) {
    $prev = '<span class="disabled">Previous Page</span>';
  } else {
    $prevpage = $currentpage - 1;
    $prev = '<a href="' . get_url('plugin/tagger/index/') . '' . $prevpage .
      '">Previous Page</a>';
  }
  if ($currentpage != 0) {
    echo "<a href=" . get_url('plugin/tagger/index/') . "0>First Page</a>\n ";
  }
  else {
    echo "<span class=\"disabled\">First Page</span>";
  }
  echo $prev;
  for ($i = 0; $i <= $lastpage; $i++) {
    $j = $i + 1;
    if ($i == $currentpage)
      echo '<span class="current">'.$j.'</span>';
    else
      echo " <a href=" . get_url('plugin/tagger/index/') . "$i>$j</a>\n";
  }
  echo $next;
  if ($currentpage != $lastpage) {
    echo "\n<a href=" . get_url('plugin/tagger/index/') . "$lastpage>Last Page</a>&nbsp&nbsp;";
  }
  else {
    echo "<span class=\"disabled\">Last Page</span>";
  }
?>
</div>
