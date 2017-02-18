SELECT U.sub_id, U.pre_app_unit_slug
FROM asianinc_wptenant.gr45v_pre_app_units U
join asianinc_wptenant.gr45v_posts P
on U.sub_id = P.id
where P.post_type = 'nf_sub'
  and date(P.post_date) = date(sysdate())
order by P.post_date desc, U.sub_id, U.id
;