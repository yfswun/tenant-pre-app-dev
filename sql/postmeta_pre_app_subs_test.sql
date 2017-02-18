SELECT P.post_date, PM.post_id, PM.meta_key, PM.meta_value
FROM asianinc_tenant001.gr45v_postmeta PM
join asianinc_tenant001.gr45v_posts P
on PM.post_id = P.id
where P.post_type = 'nf_sub'
  and date(P.post_date) = date(sysdate())
order by P.post_date desc, PM.post_id, PM.meta_key
;