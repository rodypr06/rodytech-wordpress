import base64
import json
import shutil
import subprocess
from pathlib import Path

root = Path(__file__).resolve().parents[1] / '.preview'
root.mkdir(exist_ok=True)
curl = shutil.which('curl.exe') or shutil.which('curl')
if not curl:
    raise SystemExit('curl is required to copy public preview articles.')
subprocess.run([curl, '--fail', '--silent', '--show-error', 'https://blog.rodytech.ai/wp-json/wp/v2/posts?per_page=12&_embed', '--output', str(root/'blog-public-posts.json')], check=True)
posts = json.loads((root/'blog-public-posts.json').read_text(encoding='utf-8'))
items = []
for post in posts:
    media = post.get('_embedded', {}).get('wp:featuredmedia', [{}])[0]
    terms = post.get('_embedded', {}).get('wp:term', [[]])[0]
    items.append({'title': post['title']['rendered'], 'slug': post['slug'], 'content': post['content']['rendered'], 'excerpt': post['excerpt']['rendered'], 'date': post['date'], 'image': media.get('source_url'), 'categories': [t['name'] for t in terms]})
payload = base64.b64encode(json.dumps(items).encode()).decode()
php = '''<?php
require '/wordpress/wp-load.php';
$preview_image_filter = '<?php add_filter("image_downsize", function($result, $id) { $url=get_post_meta($id,"_preview_image_url",true); return $url ? array($url,1200,675,false) : $result; },10,2);';
wp_mkdir_p('/wordpress/wp-content/mu-plugins');
file_put_contents('/wordpress/wp-content/mu-plugins/local-preview-images.php', $preview_image_filter);
require '/wordpress/wp-content/mu-plugins/local-preview-images.php';
switch_theme('rodytech-theme');
update_option('blogname', 'RodyTech Journal');
update_option('blogdescription', 'Practical AI. Thoughtfully applied.');
update_option('permalink_structure', '/%postname%/');
update_option('posts_per_page', 6);
wp_update_user(array('ID'=>1,'display_name'=>'Rody','description'=>'AI consultant and systems builder at RodyTech.'));
update_user_meta(1, 'rodytech_avatar_url', get_template_directory_uri() . '/avatar-rody.svg');
foreach (get_posts(array('post_type'=>array('post','page'),'numberposts'=>-1)) as $p) wp_delete_post($p->ID, true);
$items = json_decode(base64_decode('PAYLOAD'),true);
foreach ($items as $item) {
  $categories = array();
  foreach($item['categories'] as $name) { $term = term_exists($name,'category'); if (!$term) $term = wp_insert_term($name,'category'); if (!is_wp_error($term)) $categories[] = (int)(is_array($term)?$term['term_id']:$term); }
  $id=wp_insert_post(array('post_title'=>html_entity_decode($item['title']),'post_name'=>$item['slug'],'post_content'=>$item['content'],'post_excerpt'=>$item['excerpt'],'post_status'=>'publish','post_author'=>1,'post_date'=>$item['date'],'post_category'=>$categories));
  if($item['image']) {
    $attachment=wp_insert_attachment(array('post_title'=>'Article image','post_mime_type'=>'image/jpeg','guid'=>$item['image'],'post_status'=>'inherit'),false,$id);
    wp_update_attachment_metadata($attachment,array('width'=>1200,'height'=>675,'file'=>''));
    update_post_meta($attachment,'_preview_image_url',$item['image']);
    update_post_meta($id,'_thumbnail_id',$attachment);
  }
}
foreach(array('Articles'=>'page-articles.php','About'=>'page-about.php') as $title=>$template) { $id=wp_insert_post(array('post_type'=>'page','post_title'=>$title,'post_status'=>'publish')); update_post_meta($id,'_wp_page_template',$template); }
$qa=wp_insert_post(array('post_title'=>'Reading layout: a practical workflow review','post_name'=>'reading-layout-review','post_status'=>'publish','post_date'=>'2025-01-01 12:00:00','post_content'=>'<p>This local preview fixture checks comfortable reading, long links, code, and tables. It is not published to the live blog.</p><h2>Make the handoff visible</h2><p>A useful system makes ownership clear. Every handoff should have a trigger, an owner, and a way to recover when the next step cannot complete.</p><blockquote>Keep human approval where context matters.</blockquote><h3>Compare the choices</h3><table><thead><tr><th>Workflow</th><th>Owner</th><th>Approval rule</th></tr></thead><tbody><tr><td>Incoming inquiry</td><td>Sales coordinator</td><td>Review before a personalized proposal is sent</td></tr></tbody></table><pre><code>const workflow = { trigger: "inquiry.received", next: "prepare_response_for_review", approvalRequired: true };</code></pre><p><a href="https://www.rodytech.ai/">Explore RodyTech</a></p>'));
flush_rewrite_rules();
'''.replace('PAYLOAD', payload)
blueprint={'landingPage':'/','steps':[{'step':'runPHP','code':php}]}
(root/'blog-preview-blueprint.json').write_text(json.dumps(blueprint),encoding='utf-8')
print('Prepared local preview with',len(items),'published article copies and a reading-layout fixture.')
