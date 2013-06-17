-- MAKE SURE the product table is brand new when this gets run!

insert into single_track.product
(product_id, parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
1,
null,
0,
'Products We Sell',
null,
null,
null,
null,
'/images/Front_COUNTER.JPG'
);

insert into single_track.product
(product_id, parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
2,
1,
1,
'Bikes',
'Bike brands we sell',
null,
null,
null,
'/images/SDC10465.JPG'
);

insert into single_track.product
(parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
2,
2,
'Trek',
'Trek makes awesome bikes! This description could be longer.',
'http://www.trekbikes.com/us/en/',
'Visit Trek\'s website.',
'/images/trek-logo-large-white-text.png',
'/images/ION_PRO.jpg'
);

insert into single_track.product
(parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
2,
2,
'Surly',
'Surly makes some awesome bikes. Needs more text here.',
'http://surlybikes.com/',
'Visit Surly\'s website.',
'/images/surly-logo.gif',
'/images/SDC10465.JPG'
);