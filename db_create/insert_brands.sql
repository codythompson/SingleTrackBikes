insert into single_track.product
(product_type_id, `name`, offsite_url, short_descr, logo_url)
values (
1,
'Trek',
'http://trekbikes.com',
'Trek makes awesome bikes. More description here plz. scelerisque ante sollicitudin commodo. Cras purus odio,vestibulum in vulputate at, tempus viverra turpis.',
'/images/trek-logo-large-white-text.png');

set @trek_id = last_insert_id();

insert into single_track.product
(product_type_id, `name`, offsite_url, short_descr, logo_url)
values (
1,
'Surly',
'http://surlybikes.com',
'Surly makes awesome bikes. More description here plz. scelerisque ante sollicitudin commodo. Cras purus odio,vestibulum in vulputate at, tempus viverra turpis.',
'/images/surly-logo.gif');

set @surly_id = last_insert_id();


-- categories

-- trek
insert into single_track.product
(product_type_id, product_parent_id, `name`)
values
(1, @trek_id, 'Road');

insert into single_track.product
(product_type_id, product_parent_id, `name`)
values
(1, @trek_id, 'Mountain');

insert into single_track.product
(product_type_id, product_parent_id, `name`)
values
(1, @trek_id, 'Town');

-- surly
insert into single_track.product
(product_type_id, product_parent_id, `name`)
values
(1, @surly_id, 'Omniterra');

set @surl_omni_id = last_insert_id();

insert into single_track.product
(product_type_id, product_parent_id, `name`)
values
(1, @surly_id, 'Pavement');

insert into single_track.product
(product_type_id, product_parent_id, `name`)
values
(1, @surly_id, 'Haulin\'');

insert into single_track.product
(product_type_id, product_parent_id, `name`)
values
(1, @surly_id, 'Trail');

-- actual bikes
insert into single_track.product
(product_type_id, product_parent_id, `name`, offsite_url, logo_url, large_logo_url)
values
(1, @surl_omni_id, 'Moonlander', 'http://surlybikes.com/bikes/moonlander', '/images/surly_moonlander_small.png', '/images/surly_moonlander_large.png');