<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Files Google reads, built from the site itself so they always carry the right address:
|  yoursite/sitemap.xml  the page and every product image (image sitemap)
|  yoursite/robots.txt   what may be crawled + the sitemap address
|  yoursite/Traboulsi-Est  the address of the old site, sent to the home page (301)
*/
class Seo extends CI_Controller {

	public function sitemap()
	{
		$url = base_url();
		$products = $this->fct->getAll('products', 'sort_order');
		$lastmod = '';
		foreach ($this->db->query("SELECT MAX(updated_date) m FROM products WHERE deleted=0 UNION ALL SELECT MAX(updated_date) FROM home_page_text")->result_array() as $row) {
			if (!empty($row['m']) && strtotime($row['m']) && $row['m'] > $lastmod) $lastmod = $row['m'];
		}
		$lastmod = ($lastmod != '' && strtotime($lastmod) < time()) ? date('Y-m-d', strtotime($lastmod)) : date('Y-m-d');
		$x = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$x .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";
		$x .= "<url>\n<loc>".$this->_x($url)."</loc>\n<lastmod>".$lastmod."</lastmod>\n<changefreq>weekly</changefreq>\n<priority>1.0</priority>\n";
		$x .= '<xhtml:link rel="alternate" hreflang="en" href="'.$this->_x($url).'"/>'."\n".'<xhtml:link rel="alternate" hreflang="ar" href="'.$this->_x($url.'ar').'"/>'."\n";
		foreach ($products as $product) {
			if ($product['image'] == '' || !is_file('uploads/products/290x220/'.$product['image'])) continue;
			$title = trim(html_entity_decode(strip_tags($product['title']), ENT_QUOTES, 'UTF-8'));
			$caption = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string)$product['description']), ENT_QUOTES, 'UTF-8')));
			$x .= "<image:image>\n<image:loc>".$this->_x($url.'uploads/products/290x220/'.$product['image'])."</image:loc>\n<image:title>".$this->_x($title)."</image:title>\n".($caption != '' ? "<image:caption>".$this->_x($caption)."</image:caption>\n" : '')."</image:image>\n";
		}
		$x .= "</url>\n";
		// the Arabic page of the bilingual design
		$x .= "<url>\n<loc>".$this->_x($url.'ar')."</loc>\n<lastmod>".$lastmod."</lastmod>\n<changefreq>weekly</changefreq>\n<priority>0.9</priority>\n</url>\n";
		// the photo and video pages, in both languages
		foreach (array('photos', 'videos') as $page) {
			foreach (array($url.$page, $url.'ar/'.$page) as $loc) {
				$x .= "<url>\n<loc>".$this->_x($loc)."</loc>\n<lastmod>".$lastmod."</lastmod>\n<changefreq>weekly</changefreq>\n<priority>0.7</priority>\n";
				$x .= '<xhtml:link rel="alternate" hreflang="en" href="'.$this->_x($url.$page).'"/>'."\n".'<xhtml:link rel="alternate" hreflang="ar" href="'.$this->_x($url.'ar/'.$page).'"/>'."\n</url>\n";
			}
		}
		// one page per sector, the export page, the guides, one page per category and one per product: each in English and in Arabic
		$this->load->helper('o5');
		$this->load->model('site_content');
		$pages = array();
		foreach ($this->site_content->sectors() as $sector) $pages[] = array($sector['path'], '0.9', '');
		foreach (array('products', 'services', 'about', 'clients', 'faq', 'contact') as $part) $pages[] = array($part, '0.8', '');
		$pages[] = array('export', '0.6', '');
		$pages[] = array('guides', '0.6', '');
		foreach ($this->site_content->guides() as $slug => $guide) $pages[] = array('guides/'.$slug, '0.7', '');
		// the same addresses as controllers/home.php: the categories in their order, then the products of each that have a photo
		$by_cat = array();
		foreach ($products as $product) {
			if ($product['image'] != '' && is_file('uploads/products/290x220/'.$product['image'])) $by_cat[$product['id_category']][] = $product;
		}
		$used = array();
		foreach ($this->fct->getAll('category', 'sort_order') as $category) {
			if (empty($by_cat[$category['id_category']])) continue;
			$pages[] = array('products/'.o5_slug($category['title']), '0.8', '');
			foreach ($by_cat[$category['id_category']] as $product) {
				$title = trim(html_entity_decode(strip_tags($product['title']), ENT_QUOTES, 'UTF-8'));
				$dir = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/' : 'uploads/products/290x220/';
				$image = "<image:image>\n<image:loc>".$this->_x($url.$dir.$product['image'])."</image:loc>\n<image:title>".$this->_x($title)."</image:title>\n</image:image>\n";
				$pages[] = array('product/'.o5_product_slug($product['title'], $product['id_products'], $used), '0.6', $image);
			}
		}
		foreach ($pages as $p) {
			foreach (array($url.$p[0], $url.'ar/'.$p[0]) as $loc) {
				$x .= "<url>\n<loc>".$this->_x($loc)."</loc>\n<lastmod>".$lastmod."</lastmod>\n<changefreq>monthly</changefreq>\n<priority>".$p[1]."</priority>\n";
				$x .= '<xhtml:link rel="alternate" hreflang="en" href="'.$this->_x($url.$p[0]).'"/>'."\n".'<xhtml:link rel="alternate" hreflang="ar" href="'.$this->_x($url.'ar/'.$p[0]).'"/>'."\n".$p[2]."</url>\n";
			}
		}
		$x .= "</urlset>\n";
		$this->output->set_content_type('application/xml; charset=utf-8')->set_output($x);
	}

	public function robots()
	{
		$url = base_url();
		$t = "User-agent: *\n";
		$t .= "Allow: /\n";
		foreach (array('application/', 'system/', 'docs/', 'cgi-bin/', 'back_office/', 'cms', 'index.php/back_office/', 'option5') as $path) {
			$t .= "Disallow: /".$path."\n";
		}
		$t .= "\nSitemap: ".$url."sitemap.xml\n";
		$this->output->set_content_type('text/plain; charset=utf-8')->set_output($t);
	}

	// the address of the old website, kept for the links Google still has
	public function old()
	{
		header('Location: '.base_url(), TRUE, 301);
		exit;
	}

	function _x($s)
	{
		return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
	}
}
