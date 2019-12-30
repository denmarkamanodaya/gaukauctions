<?php

?>
@if($problemCount)
<li>
    <a href="/admin/dealers/problems" class="" data-toggle="tooltip" title="Problems">
        <i class="far fa-exclamation-triangle"></i>
        <span class="visible-xs-inline-block position-right">Problems</span>
        <span class="badge bg-warning-400">{{$problemCount}}</span>
    </a>
</li>
@endif
