SELECT * FROM asianinc_wptenant.gr45v_posts
where post_type = 'nf_sub'
  and date(post_date) = date(sysdate())
order by post_date desc;