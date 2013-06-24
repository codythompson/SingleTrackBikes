-- MAKE SURE the product table is brand new when this gets run!

insert into single_track.product
(product_id, parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url, permanent)
values (
1,
null,
0,
'Products We Sell',
null,
null,
null,
null,
'/images/Front_COUNTER.JPG',
1
);

insert into single_track.product
(product_id, parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url, permanent)
values (
2,
1,
1,
'Bikes',
'Bike brands we sell',
null,
null,
null,
'/images/SDC10465.JPG',
1
);

insert into single_track.product
(product_id, parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url, permanent)
values (
3,
1,
1,
'Parts',
'Parts we sell',
null,
null,
null,
'/images/FRONT_COUNTER.JPG',
1
);

insert into single_track.product
(product_id, parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url, permanent)
values (
4,
1,
1,
'Gear',
'Gear we sell',
null,
null,
null,
'/images/FRONT_COUNTER.JPG',
1
);

insert into single_track.product
(parent_product_id, product_style_id, `name`, descr, 
 long_descr, 
offsite_url, offsite_url_text, image_url, background_image_url)
values (
2,
2,
'Trek',
'Trek makes awesome bikes! This description could be longer.',
 '"When Trek began in 1976, our mission was simple: Build the best bikes in the world. Today, we’ve added to our mission: Help the world use the bicycle as a simple solution to complex problems." - <a href="http://trekbikes.com">trekbikes.com</a>',
'http://www.trekbikes.com/us/en/',
'Visit Trek\'s website.',
'/images/trek-logo-large-white-text.png',
'/images/ION_PRO.jpg'
);

set @trekId = last_insert_id();

insert into single_track.product
(parent_product_id, product_style_id, `name`, descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
2,
1,
'Surly',
'Surly makes some awesome bikes. Needs more text here.',
'http://surlybikes.com/',
'Visit Surly\'s website.',
'/images/surly-logo.gif',
'/images/SDC10465.JPG'
);

insert into single_track.product
(parent_product_id, product_style_id, `name`, descr, long_descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
@trekId,
1,
'Trek Road Bikes',
'Light, fast bikes designed to fly over pavement. For racing, or recreating, or both.',
'"Trek started out making handcrafted road bikes. Our founding mission: build the best bikes in the world. And that\'s exactly what we do, from our legendary Pro Tour race bikes to refined, affordable all-aluminum models. Our arsenal of exclusive technology offers a solution for every obstacle you might find on the ride. We own the road, and so can you." - <a href="http://trekbikes.com">trekbikes.com</a>',
'http://www.trekbikes.com/us/en/bikes/road',
'Visit Trek\'s Road Bikes Page',
'/images/trek_road_bike.jpg',
'/images/SDC10461.JPG'
);

insert into single_track.product
(parent_product_id, product_style_id, `name`, descr, long_descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
@trekId,
1,
'Trek Mountain Bikes',
'Sure-footed off-road bikes built to conquer any trail, from tame to treacherous.',
'"Trek is the world leader in mountain bike technology. No surprise that our mountain bikes are the most technologically advanced on the market. Each platform leads its class, and every model is loaded with features and details that will make any ride, on any trail, better." - <a href="http://trekbikes.com">trekbikes.com</a>',
'http://www.trekbikes.com/us/en/bikes/mountain',
'Visit Trek\'s Mountain Bikes Page',
'/images/trek_mtn_bike.jpg',
'/images/orange_2.JPG'
);

insert into single_track.product
(parent_product_id, product_style_id, `name`, descr, long_descr, offsite_url, offsite_url_text, image_url, background_image_url)
values (
@trekId,
1,
'Trek Town Bikes',
'Bikes that let you live the two-wheeled life. Haul, commute, get fit, represent, have fun.',
'"Trek town bikes have an agenda: make the world a happier, healthier place by getting more people on bikes more often. We make bikes for every purpose and every rider: smart, fun, bikes that fit the way you work, play, and live. Go by bike!" - <a href="http://trekbikes.com">trekbikes.com</a>',
'http://www.trekbikes.com/us/en/bikes/town',
'Visit Trek\'s Town Bikes Page',
'/images/trek_town_bike.jpg',
'/images/SDC10461.JPG'
);