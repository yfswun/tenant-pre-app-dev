SELECT * FROM asianinc_tenant001.gr45v_posts
where post_type = 'nf_sub'
  and date(post_date) = date(sysdate())
order by post_date desc;