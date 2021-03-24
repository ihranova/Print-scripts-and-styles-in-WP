<?php
	add_filter("request", "wprl_change_term_request", 1, 1);
	function wprl_change_term_request($query)
		{
			$tax_name = "domain_category";

			if (isset($query["attachment"]))
				{
					$include_children = true;
					$name             = $query["attachment"];
				}
			else
				{
					$include_children = false;
					$name             = (isset($query["name"])) ? $query["name"] : null;
				}

			$term = get_term_by("slug", $name, $tax_name);
			if (isset($name) && $term && !is_wp_error($term))
				{
					if ($include_children)
						{
							unset($query["attachment"]);
							$parent = $term->parent;
							while ($parent)
								{
									$parent_term = get_term($parent, $tax_name);
									$name        = $parent_term->slug . "/" . $name;
									$parent      = $parent_term->parent;
								}
						}
					else
						{
							unset($query["name"]);
						}

					switch ($tax_name)
						{
							case "category":
									$query["category_name"] = $name;
								break;
							case "post_tag":
									$query["tag"] = $name;
								break;
							default:
									$query[$tax_name] = $name;
						}
				}

			return $query;
		}
	add_filter("term_link", "wprl_term_permalink", 10, 3);
	function wprl_term_permalink($url, $term, $taxonomy)
		{
			$taxonomy_name = "domain_category";
			$taxonomy_slug = "dtag";

			if (strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name)
				return $url;

			$url = str_replace("/" . $taxonomy_slug, "", $url);

			return $url;
		}

	add_action("template_redirect", "wprl_old_term_redirect");
	function wprl_old_term_redirect()
		{
			$taxonomy_name = "domain_category";
			$taxonomy_slug = "dtag";

			if (strpos($_SERVER["REQUEST_URI"], $taxonomy_slug) === FALSE)
					return;
			if ((is_category() && $taxonomy_name == "category") || (is_tag() && $taxonomy_name == "post_tag") || is_tax($taxonomy_name)):
					wp_redirect(site_url(str_replace($taxonomy_slug, "", $_SERVER["REQUEST_URI"])), 301);
					exit();
			endif;
		}

?>
