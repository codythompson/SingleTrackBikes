<?php
define("ST_MISC_TEXT_LOC_NAME", "Location Text");
define("ST_MISC_TEXT_ADDRESS_NAME", "Address");
define("ST_MISC_TEXT_PHONE_NAME", "Phone Number");
define("ST_MISC_TEXT_HOURS_NAME", "Hours");

$locText = getMiscText(ST_MISC_TEXT_LOC_NAME);
$addText = getMiscText(ST_MISC_TEXT_ADDRESS_NAME);
$phoneText = getMiscText(ST_MISC_TEXT_PHONE_NAME);
$hoursText = getMiscText(ST_MISC_TEXT_HOURS_NAME);
?>
<div class="well">

<h2>Location and Contact info</h2>

<p>
<?php echo $locText; ?>
</p>

<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Single+Track+Bikes,+575+Riordan+Road,+Flagstaff,+AZ+86001&amp;aq=0&amp;oq=Single+575+Riordan+Road+Flagstaff,+AZ+86001&amp;sll=35.189629,-111.660274&amp;sspn=0.006541,0.007746&amp;t=m&amp;g=575+Riordan+Road+Flagstaff,+AZ+86001&amp;ie=UTF8&amp;hq=Single+Track+Bikes,&amp;hnear=575+Riordan+Rd,+Flagstaff,+Coconino,+Arizona+86001&amp;cid=18218464537185495342&amp;ll=35.192276,-111.659739&amp;spn=0.006137,0.00912&amp;z=16&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Single+Track+Bikes,+575+Riordan+Road,+Flagstaff,+AZ+86001&amp;aq=0&amp;oq=Single+575+Riordan+Road+Flagstaff,+AZ+86001&amp;sll=35.189629,-111.660274&amp;sspn=0.006541,0.007746&amp;t=m&amp;g=575+Riordan+Road+Flagstaff,+AZ+86001&amp;ie=UTF8&amp;hq=Single+Track+Bikes,&amp;hnear=575+Riordan+Rd,+Flagstaff,+Coconino,+Arizona+86001&amp;cid=18218464537185495342&amp;ll=35.192276,-111.659739&amp;spn=0.006137,0.00912&amp;z=16&amp;iwloc=A" style="color:#0000FF;text-align:left">View Larger Map</a></small>

<div>
    <h3>Address</h3>
    <p><?php echo $addText; ?></p>
</div>

<div>
    <h3>Phone</h3>
    <a href="tel:<?php echo $phoneText; ?>"><?php echo $phoneText; ?></a>
</div>

<div>
    <h3>Business Hours</h3>
    <p><?php echo $hoursText; ?></p>
</div>

</div>
