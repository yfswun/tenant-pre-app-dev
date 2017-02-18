select P.ID, U.pre_app_unit_slug, P.post_author, P.post_date, P.post_modified
from gr45v_posts P
left join gr45v_pre_app_units U
on P.ID = U.sub_id
where P.post_type = 'nf_sub'
order by P.ID desc
;