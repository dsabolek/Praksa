<?php
/**
 * The autoloader class mappings.
 *
 * @package    Smartcrawl
 * @subpackage Class mappings.
 */

return array(
	// Classes in includes/core/.
	'Smartcrawl_Base_Controller'                          => '/core/class-wds-base-controller.php',
	'Smartcrawl_Checks'                                   => '/core/class-wds-checks.php',
	'Smartcrawl_Compatibility'                            => '/core/class-wds-compatibility.php',
	'Smartcrawl_Controller_Ajax_Search'                   => '/core/class-wds-controller-ajax-search.php',
	'Smartcrawl_Controller_Analysis'                      => '/core/class-wds-controller-analysis.php',
	'Smartcrawl_Controller_Analysis_Content'              => '/core/class-wds-controller-analysis-content.php',
	'Smartcrawl_Controller_Assets'                        => '/core/class-wds-controller-assets.php',
	'Smartcrawl_Controller_Cron'                          => '/core/class-wds-controller-cron.php',
	'Smartcrawl_Controller_Data'                          => '/core/class-wds-controller-data.php',
	'Smartcrawl_Controller_Hub_Abstract'                  => '/core/class-wds-controller-hub-abstract.php',
	'Smartcrawl_Controller_Hub'                           => '/core/class-wds-controller-hub.php',
	'Smartcrawl_Controller_Onboard'                       => '/core/class-wds-controller-onboard.php',
	'Smartcrawl_Controller_Pointers'                      => '/core/class-wds-controller-pointers.php',
	'Smartcrawl_Controller_Robots'                        => '/core/class-wds-controller-robots.php',
	'Smartcrawl_Controller_Welcome'                       => '/core/class-wds-controller-welcome.php',
	'Smartcrawl_White_Label'                              => '/core/class-wds-white-label.php',
	'Smartcrawl_Core_Request'                             => '/core/class-wds-core-request.php',
	'Smartcrawl_Endpoint_Resolver'                        => '/core/class-wds-endpoint-resolver.php',
	'Smartcrawl_Html'                                     => '/core/class-wds-html.php',
	'Smartcrawl_Logger'                                   => '/core/class-wds-logger.php',
	'Smartcrawl_Macro'                                    => '/core/class-wds-macro.php',
	'Smartcrawl_Model'                                    => '/core/class-wds-model.php',
	'Smartcrawl_Model_Analysis'                           => '/core/class-wds-model-analysis.php',
	'Smartcrawl_Model_Ignores'                            => '/core/class-wds-model-ignores.php',
	'Smartcrawl_Model_User'                               => '/core/class-wds-model-user.php',
	'Smartcrawl_Renderable'                               => '/core/class-wds-renderable.php',
	'Smartcrawl_Sitewide_Export'                          => '/core/class-wds-sitewide-export.php',
	'Smartcrawl_Simple_Renderer'                          => '/core/class-wds-simple-renderer.php',
	'Smartcrawl_SeoReport'                                => '/core/class-wds-seo-report.php',
	'Smartcrawl_Service'                                  => '/core/class-wds-service.php',
	'Smartcrawl_Settings'                                 => '/core/class-wds-settings.php',
	'Smartcrawl_String_Utils'                             => '/core/class-wds-string-utils.php',
	'Smartcrawl_String'                                   => '/core/class-wds-string.php',
	'Smartcrawl_Syllable'                                 => '/core/class-wds-syllable.php',
	'Smartcrawl_WorkUnit'                                 => '/core/class-wds-work-unit.php',
	'Smartcrawl_Youtube_Data_Fetcher'                     => '/core/class-wds-youtube-data-fetcher.php',
	'Smartcrawl_Controller_Plugin_Links'                  => '/core/class-wds-controller-plugin-links.php',
	'Smartcrawl_Report_Permalinks_Controller'             => '/core/class-wds-report-permalinks.php',
	'Smartcrawl_Recommended_Plugins'                      => '/core/class-wds-recommended-plugins.php',
	'Smartcrawl_Dashboard_Notices'                        => '/core/class-wds-dashboard-notices.php',
	// Classes in includes/core/service/.
	'Smartcrawl_Lighthouse_Service'                       => '/core/service/class-wds-lighthouse-service.php',
	'Smartcrawl_Seo_Service'                              => '/core/service/class-wds-seo-service.php',
	'Smartcrawl_Site_Service'                             => '/core/service/class-wds-site-service.php',
	'Smartcrawl_Uptime_Service'                           => '/core/service/class-wds-uptime-service.php',
	// Classes in includes/core/admin-page/.
	'Smartcrawl_Admin_Page'                               => '/core/admin-page/class-wds-admin-page.php',
	'Smartcrawl_Controller_Upgrade_Page'                  => '/core/admin-page/class-wds-controller-upgrade-page.php',
	'Smartcrawl_Network_Settings_Page_Controller'         => '/core/admin-page/class-wds-network-settings-page-controller.php',
	// Classes in includes/core/buddypress/.
	'Smartcrawl_Buddypress_Api'                           => '/core/buddypress/class-wds-buddypress-api.php',
	// Classes in includes/core/cache/.
	'Smartcrawl_Cache_Manager'                            => '/core/cache/class-wds-cache-manager.php',
	'Smartcrawl_Post_Cache'                               => '/core/cache/class-wds-post-cache.php',
	'Smartcrawl_Term_Cache'                               => '/core/cache/class-wds-term-cache.php',
	'Smartcrawl_String_Cache'                             => '/core/cache/class-wds-string-cache.php',
	'Smartcrawl_Object_Cache'                             => '/core/cache/class-wds-object-cache.php',
	// Classes in includes/core/checks/.
	'Smartcrawl_Check_Post_Abstract'                      => '/core/checks/class-wds-check-post-abstract.php',
	'Smartcrawl_Check_Abstract'                           => '/core/checks/class-wds-check-abstract.php',
	'Smartcrawl_Check_Content_Length'                     => '/core/checks/class-wds-check-content-length.php',
	'Smartcrawl_Check_Focus'                              => '/core/checks/class-wds-check-focus.php',
	'Smartcrawl_Check_Focus_Stopwords'                    => '/core/checks/class-wds-check-focus-stopwords.php',
	'Smartcrawl_Check_Imgalts_Keywords'                   => '/core/checks/class-wds-check-imgalts-keywords.php',
	'Smartcrawl_Check_Keyword_Density'                    => '/core/checks/class-wds-check-keyword-density.php',
	'Smartcrawl_Check_Keywords_Used'                      => '/core/checks/class-wds-check-keywords-used.php',
	'Smartcrawl_Check_Links_Count'                        => '/core/checks/class-wds-check-links-count.php',
	'Smartcrawl_Check_Metadesc_Keywords'                  => '/core/checks/class-wds-check-metadesc-keywords.php',
	'Smartcrawl_Check_Metadesc_Handcraft'                 => '/core/checks/class-wds-check-metadesc-handcraft.php',
	'Smartcrawl_Check_Metadesc_Length'                    => '/core/checks/class-wds-check-metadesc-length.php',
	'Smartcrawl_Check_Para_Keywords'                      => '/core/checks/class-wds-check-para-keywords.php',
	'Smartcrawl_Check_Slug_Keywords'                      => '/core/checks/class-wds-check-slug-keywords.php',
	'Smartcrawl_Check_Slug_Underscores'                   => '/core/checks/class-wds-check-slug-underscores.php',
	'Smartcrawl_Check_Subheadings_Keywords'               => '/core/checks/class-wds-check-subheadings-keywords.php',
	'Smartcrawl_Check_Title_Keywords'                     => '/core/checks/class-wds-check-title-keywords.php',
	'Smartcrawl_Check_Title_Secondary_Keywords'           => '/core/checks/class-wds-check-title-secondary-keywords.php',
	'Smartcrawl_Check_Title_Length'                       => '/core/checks/class-wds-check-title-length.php',
	'Smartcrawl_Check_Bolded_Keyword'                     => '/core/checks/class-wds-check-bolded-keyword.php',
	'Smartcrawl_Check_Nofollow_Links'                     => '/core/checks/class-wds-check-nofollow-links.php',
	// Classes in includes/core/configs.
	'Smartcrawl_Config_Collection'                        => '/core/configs/class-wds-config-collection.php',
	'Smartcrawl_Configs_Service'                          => '/core/configs/class-wds-configs-service.php',
	'Smartcrawl_Controller_Configs'                       => '/core/configs/class-wds-controller-configs.php',
	'Smartcrawl_Config_Model'                             => '/core/configs/class-wds-config-model.php',
	'Smartcrawl_Export'                                   => '/core/configs/class-wds-export.php',
	'Smartcrawl_Import'                                   => '/core/configs/class-wds-import.php',
	'Smartcrawl_Model_IO'                                 => '/core/configs/class-wds-model-io.php',
	// Classes in includes/core/crawler.
	'Smartcrawl_Controller_Crawler'                       => '/core/crawler/class-wds-controller-crawler.php',
	// Classes in includes/core/lighthouse/.
	'Smartcrawl_Controller_Lighthouse'                    => '/core/lighthouse/class-wds-controller-lighthouse.php',
	'Smartcrawl_Lighthouse_Dashboard_Renderer'            => '/core/lighthouse/class-wds-lighthouse-dashboard-renderer.php',
	'Smartcrawl_Lighthouse_Group'                         => '/core/lighthouse/class-wds-lighthouse-group.php',
	'Smartcrawl_Lighthouse_Options'                       => '/core/lighthouse/class-wds-lighthouse-options.php',
	'Smartcrawl_Lighthouse_Renderer'                      => '/core/lighthouse/class-wds-lighthouse-renderer.php',
	'Smartcrawl_Lighthouse_Report'                        => '/core/lighthouse/class-wds-lighthouse-report.php',
	// Classes in includes/core/entities/.
	'Smartcrawl_404_Page'                                 => '/core/entities/class-wds-404-page.php',
	'Smartcrawl_Blog_Home'                                => '/core/entities/class-wds-blog-home.php',
	'Smartcrawl_Buddypress_Group'                         => '/core/entities/class-wds-buddypress-group.php',
	'Smartcrawl_Buddypress_Profile'                       => '/core/entities/class-wds-buddypress-profile.php',
	'Smartcrawl_Date_Archive'                             => '/core/entities/class-wds-date-archive.php',
	'Smartcrawl_Entity'                                   => '/core/entities/class-wds-entity.php',
	'Smartcrawl_Entity_With_Archive'                      => '/core/entities/class-wds-entity-with-archive.php',
	'Smartcrawl_Post'                                     => '/core/entities/class-wds-post.php',
	'Smartcrawl_Post_Author'                              => '/core/entities/class-wds-post-author.php',
	'Smartcrawl_Post_Type'                                => '/core/entities/class-wds-post-type.php',
	'Smartcrawl_Product'                                  => '/core/entities/class-wds-product.php',
	'Smartcrawl_Search_Page'                              => '/core/entities/class-wds-search-page.php',
	'Smartcrawl_Static_Home'                              => '/core/entities/class-wds-static-home.php',
	'Smartcrawl_Taxonomy_Term'                            => '/core/entities/class-wds-taxonomy-term.php',
	'Smartcrawl_Woo_Shop_Page'                            => '/core/entities/class-wds-woo-shop-page.php',
	// Classes in includes/core/multisite.
	'Smartcrawl_Subsite_Process_Runner'                   => '/core/multisite/class-wds-subsite-process-runner.php',
	'Smartcrawl_Network_Configs_Controller'               => '/core/multisite/class-wds-network-configs-controller.php',
	'Smartcrawl_Sitewide_Deprecation_Controller'          => '/core/multisite/class-wds-sitewide-deprecation-controller.php',
	// Classes in includes/core/readability-analysis/formulas/.
	'Smartcrawl_Readability_Formula'                      => '/core/readability-analysis/formulas/class-wds-readability-formula.php',
	'Smartcrawl_Readability_Formula_Flesch'               => '/core/readability-analysis/formulas/class-wds-readability-formula-flesch.php',
	// Classes in includes/core/readability-analysis/.
	'Smartcrawl_Controller_Readability'                   => '/core/readability-analysis/class-wds-controller-readability.php',
	// Classes in includes/core/redirects.
	'Smartcrawl_217_Redirect_Upgrade'                     => '/core/redirects/class-wds-217-redirect-upgrade.php',
	'Smartcrawl_Controller_Redirection'                   => '/core/redirects/class-wds-controller-redirection.php',
	'Smartcrawl_Model_Redirection'                        => '/core/redirects/class-wds-model-redirection.php',
	'Smartcrawl_Redirect_Item'                            => '/core/redirects/class-wds-redirect-item.php',
	'Smartcrawl_Redirect_Utils'                           => '/core/redirects/class-wds-redirect-utils.php',
	'Smartcrawl_Redirects_Database_Table'                 => '/core/redirects/class-wds-redirects-database-table.php',
	// Classes in includes/core/lighthouse/checks/.
	'Smartcrawl_Lighthouse_Canonical_Check'               => '/core/lighthouse/checks/class-wds-lighthouse-canonical-check.php',
	'Smartcrawl_Lighthouse_Check'                         => '/core/lighthouse/checks/class-wds-lighthouse-check.php',
	'Smartcrawl_Lighthouse_Crawlable_Anchors_Check'       => '/core/lighthouse/checks/class-wds-lighthouse-crawlable-anchors-check.php',
	'Smartcrawl_Lighthouse_Document_Title_Check'          => '/core/lighthouse/checks/class-wds-lighthouse-document-title-check.php',
	'Smartcrawl_Lighthouse_Font_Size_Check'               => '/core/lighthouse/checks/class-wds-lighthouse-font-size-check.php',
	'Smartcrawl_Lighthouse_Hreflang_Check'                => '/core/lighthouse/checks/class-wds-lighthouse-hreflang-check.php',
	'Smartcrawl_Lighthouse_Http_Status_Code_Check'        => '/core/lighthouse/checks/class-wds-lighthouse-http-status-code-check.php',
	'Smartcrawl_Lighthouse_Image_Alt_Check'               => '/core/lighthouse/checks/class-wds-lighthouse-image-alt-check.php',
	'Smartcrawl_Lighthouse_Is_Crawlable_Check'            => '/core/lighthouse/checks/class-wds-lighthouse-is-crawlable-check.php',
	'Smartcrawl_Lighthouse_Link_Text_Check'               => '/core/lighthouse/checks/class-wds-lighthouse-link-text-check.php',
	'Smartcrawl_Lighthouse_Meta_Description_Check'        => '/core/lighthouse/checks/class-wds-lighthouse-meta-description-check.php',
	'Smartcrawl_Lighthouse_Plugins_Check'                 => '/core/lighthouse/checks/class-wds-lighthouse-plugins-check.php',
	'Smartcrawl_Lighthouse_Robots_Txt_Check'              => '/core/lighthouse/checks/class-wds-lighthouse-robots-txt-check.php',
	'Smartcrawl_Lighthouse_Structured_Data_Check'         => '/core/lighthouse/checks/class-wds-lighthouse-structured-data-check.php',
	'Smartcrawl_Lighthouse_Tap_Targets_Check'             => '/core/lighthouse/checks/class-wds-lighthouse-tap-targets-check.php',
	'Smartcrawl_Lighthouse_Viewport_Check'                => '/core/lighthouse/checks/class-wds-lighthouse-viewport-check.php',
	// Classes in includes/core/lighthouse/tables/.
	'Smartcrawl_Lighthouse_Table'                         => '/core/lighthouse/tables/class-wds-lighthouse-table.php',
	'Smartcrawl_Lighthouse_Tap_Targets_Table'             => '/core/lighthouse/tables/class-wds-lighthouse-tap-targets-table.php',
	// Classes in includes/core/schema/fragments/.
	'Smartcrawl_Schema_Fragment'                          => '/core/schema/fragments/class-wds-schema-fragment.php',
	'Smartcrawl_Schema_Fragment_Archive'                  => '/core/schema/fragments/class-wds-schema-fragment-archive.php',
	'Smartcrawl_Schema_Fragment_Article'                  => '/core/schema/fragments/class-wds-schema-fragment-article.php',
	'Smartcrawl_Schema_Fragment_Author_Archive'           => '/core/schema/fragments/class-wds-schema-fragment-author-archive.php',
	'Smartcrawl_Schema_Fragment_Blog_Home'                => '/core/schema/fragments/class-wds-schema-fragment-blog-home.php',
	'Smartcrawl_Schema_Fragment_Blog_Home_Webpage'        => '/core/schema/fragments/class-wds-schema-fragment-blog-home-webpage.php',
	'Smartcrawl_Schema_Fragment_Comments'                 => '/core/schema/fragments/class-wds-schema-fragment-comments.php',
	'Smartcrawl_Schema_Fragment_Date_Archive'             => '/core/schema/fragments/class-wds-schema-fragment-date-archive.php',
	'Smartcrawl_Schema_Fragment_Footer'                   => '/core/schema/fragments/class-wds-schema-fragment-footer.php',
	'Smartcrawl_Schema_Fragment_Header'                   => '/core/schema/fragments/class-wds-schema-fragment-header.php',
	'Smartcrawl_Schema_Fragment_Media'                    => '/core/schema/fragments/class-wds-schema-fragment-media.php',
	'Smartcrawl_Schema_Fragment_Menu'                     => '/core/schema/fragments/class-wds-schema-fragment-menu.php',
	'Smartcrawl_Schema_Fragment_Minimal_Webpage'          => '/core/schema/fragments/class-wds-schema-fragment-minimal-webpage.php',
	'Smartcrawl_Schema_Fragment_Post'                     => '/core/schema/fragments/class-wds-schema-fragment-post.php',
	'Smartcrawl_Schema_Fragment_Post_Author'              => '/core/schema/fragments/class-wds-schema-fragment-post-author.php',
	'Smartcrawl_Schema_Fragment_PT_Archive'               => '/core/schema/fragments/class-wds-schema-fragment-pt-archive.php',
	'Smartcrawl_Schema_Fragment_Publisher'                => '/core/schema/fragments/class-wds-schema-fragment-publisher.php',
	'Smartcrawl_Schema_Fragment_Publishing_Person'        => '/core/schema/fragments/class-wds-schema-fragment-publishing-person.php',
	'Smartcrawl_Schema_Fragment_Search'                   => '/core/schema/fragments/class-wds-schema-fragment-search.php',
	'Smartcrawl_Schema_Fragment_Singular'                 => '/core/schema/fragments/class-wds-schema-fragment-singular.php',
	'Smartcrawl_Schema_Fragment_Static_Home'              => '/core/schema/fragments/class-wds-schema-fragment-static-home.php',
	'Smartcrawl_Schema_Fragment_Tax_Archive'              => '/core/schema/fragments/class-wds-schema-fragment-tax-archive.php',
	'Smartcrawl_Schema_Fragment_Webpage'                  => '/core/schema/fragments/class-wds-schema-fragment-webpage.php',
	'Smartcrawl_Schema_Fragment_Website'                  => '/core/schema/fragments/class-wds-schema-fragment-website.php',
	'Smartcrawl_Schema_Fragment_Woo_Shop'                 => '/core/schema/fragments/class-wds-schema-fragment-woo-shop.php',
	// Classes in includes/core/schema/loops/.
	'Smartcrawl_Schema_Loop'                              => '/core/schema/loops/class-wds-schema-loop.php',
	'Smartcrawl_Schema_Loop_Comments'                     => '/core/schema/loops/class-wds-schema-loop-comments.php',
	'Smartcrawl_Schema_Loop_Woocommerce_Reviews'          => '/core/schema/loops/class-wds-schema-loop-woocommerce-reviews.php',
	// Classes in includes/core/schema/sources/.
	'Smartcrawl_Schema_Property_Source'                   => '/core/schema/sources/class-wds-schema-property-source.php',
	'Smartcrawl_Schema_Source_Author'                     => '/core/schema/sources/class-wds-schema-source-author.php',
	'Smartcrawl_Schema_Source_Comment'                    => '/core/schema/sources/class-wds-schema-source-comment.php',
	'Smartcrawl_Schema_Source_Comment_Factory'            => '/core/schema/sources/class-wds-schema-source-comment-factory.php',
	'Smartcrawl_Schema_Source_Factory'                    => '/core/schema/sources/class-wds-schema-source-factory.php',
	'Smartcrawl_Schema_Source_Media'                      => '/core/schema/sources/class-wds-schema-source-media.php',
	'Smartcrawl_Schema_Source_Options'                    => '/core/schema/sources/class-wds-schema-source-options.php',
	'Smartcrawl_Schema_Source_Post'                       => '/core/schema/sources/class-wds-schema-source-post.php',
	'Smartcrawl_Schema_Source_Post_Meta'                  => '/core/schema/sources/class-wds-schema-source-post-meta.php',
	'Smartcrawl_Schema_Source_Schema_Settings'            => '/core/schema/sources/class-wds-schema-source-schema-settings.php',
	'Smartcrawl_Schema_Source_SEO_Meta'                   => '/core/schema/sources/class-wds-schema-source-seo-meta.php',
	'Smartcrawl_Schema_Source_Site_Settings'              => '/core/schema/sources/class-wds-schema-source-site-settings.php',
	'Smartcrawl_Schema_Source_Text'                       => '/core/schema/sources/class-wds-schema-source-text.php',
	'Smartcrawl_Schema_Source_Woocommerce'                => '/core/schema/sources/class-wds-schema-source-woocommerce.php',
	'Smartcrawl_Schema_Source_Woocommerce_Review'         => '/core/schema/sources/class-wds-schema-source-woocommerce-review.php',
	'Smartcrawl_Schema_Source_Woocommerce_Review_Factory' => '/core/schema/sources/class-wds-schema-source-woocommerce-review-factory.php',
	// Classes in includes/core/schema/types.
	'Smartcrawl_Schema_Type'                              => '/core/schema/types/class-wds-schema-type.php',
	'Smartcrawl_Schema_Type_Woo_Product'                  => '/core/schema/types/class-wds-schema-type-woo-product.php',
	// Classes in includes/core/schema/.
	'Smartcrawl_Controller_Media_Schema_Data'             => '/core/schema/class-wds-controller-media-schema-data.php',
	'Smartcrawl_Controller_Schema_Types'                  => '/core/schema/class-wds-controller-schema-types.php',
	'Smartcrawl_Schema_Printer'                           => '/core/schema/class-wds-schema-printer.php',
	'Smartcrawl_Schema_Property_Values'                   => '/core/schema/class-wds-schema-property-values.php',
	'Smartcrawl_Schema_Type_Conditions'                   => '/core/schema/class-wds-schema-type-conditions.php',
	'Smartcrawl_Schema_Type_Constants'                    => '/core/schema/class-wds-schema-type-constants.php',
	'Smartcrawl_Schema_Utils'                             => '/core/schema/class-wds-schema-utils.php',
	// Classes in includes/core/sitemaps/general/query/.
	'Smartcrawl_Sitemap_BP_Groups_Query'                  => '/core/sitemaps/general/query/class-wds-sitemap-bp-groups-query.php',
	'Smartcrawl_Sitemap_BP_Profile_Query'                 => '/core/sitemaps/general/query/class-wds-sitemap-bp-profile-query.php',
	'Smartcrawl_Sitemap_Extras_Query'                     => '/core/sitemaps/general/query/class-wds-sitemap-extras-query.php',
	'Smartcrawl_Sitemap_Posts_Query'                      => '/core/sitemaps/general/query/class-wds-sitemap-posts-query.php',
	'Smartcrawl_Sitemap_Terms_Query'                      => '/core/sitemaps/general/query/class-wds-sitemap-terms-query.php',
	// Classes in includes/core/sitemaps/general/.
	'Smartcrawl_General_Sitemap'                          => '/core/sitemaps/general/class-wds-general-sitemap.php',
	'Smartcrawl_Sitemap_Item'                             => '/core/sitemaps/general/class-wds-sitemap-item.php',
	// Classes in includes/core/sitemaps/news/.
	'Smartcrawl_News_Sitemap'                             => '/core/sitemaps/news/class-wds-news-sitemap.php',
	'Smartcrawl_News_Sitemap_Data'                        => '/core/sitemaps/news/class-wds-news-sitemap-data.php',
	'Smartcrawl_Sitemap_News_Item'                        => '/core/sitemaps/news/class-wds-sitemap-news-item.php',
	'Smartcrawl_Sitemap_News_Query'                       => '/core/sitemaps/news/class-wds-sitemap-news-query.php',
	// Classes in includes/core/sitemaps/.
	'Smartcrawl_Controller_Sitemap'                       => '/core/sitemaps/class-wds-controller-sitemap.php',
	'Smartcrawl_Controller_Sitemap_Front'                 => '/core/sitemaps/class-wds-controller-sitemap-front.php',
	'Smartcrawl_Controller_Sitemap_Native'                => '/core/sitemaps/class-wds-controller-sitemap-native.php',
	'Smartcrawl_Controller_Sitemap_Troubleshooting'       => '/core/sitemaps/class-wds-controller-sitemap-troubleshooting.php',
	'Smartcrawl_Sitemap'                                  => '/core/sitemaps/class-wds-sitemap.php',
	'Smartcrawl_Sitemap_Cache'                            => '/core/sitemaps/class-wds-sitemap-cache.php',
	'Smartcrawl_Sitemap_Index_Item'                       => '/core/sitemaps/class-wds-sitemap-index-item.php',
	'Smartcrawl_Sitemap_Post_Fetcher'                     => '/core/sitemaps/class-wds-sitemap-post-fetcher.php',
	'Smartcrawl_Sitemap_Query'                            => '/core/sitemaps/class-wds-sitemap-query.php',
	'Smartcrawl_Sitemap_Utils'                            => '/core/sitemaps/class-wds-sitemap-utils.php',
	'Smartcrawl_Sitemaps_Provider'                        => '/core/sitemaps/class-wds-sitemaps-provider.php',
	// Classes in includes/core/third-party-import/.
	'Smartcrawl_Controller_Third_Party_Import'            => '/core/third-party-import/class-wds-controller-third-party-import.php',
	'Smartcrawl_Importer'                                 => '/core/third-party-import/class-wds-importer.php',
	'Smartcrawl_AIOSEOP_Importer'                         => '/core/third-party-import/class-wds-aioseop-importer.php',
	'Smartcrawl_Yoast_Importer'                           => '/core/third-party-import/class-wds-yoast-importer.php',
	// Classes in includes/core/woocommerce/.
	'Smartcrawl_Controller_Woo_Global_Id'                 => '/core/woocommerce/class-wds-controller-woo-global-id.php',
	'Smartcrawl_Controller_Woocommerce'                   => '/core/woocommerce/class-wds-controller-woocommerce.php',
	'Smartcrawl_Woocommerce_Data'                         => '/core/woocommerce/class-wds-woocommerce-data.php',
	'Smartcrawl_Woocommerce_Api'                          => '/core/woocommerce/class-wds-woocommerce-api.php',
	// Classes in includes/core/wpml/.
	'Smartcrawl_Controller_Wpml'                          => '/core/wpml/class-wds-controller-wpml.php',
	'Smartcrawl_Wpml_Api'                                 => '/core/wpml/class-wds-wpml-api.php',
	// Classes in includes/admin/.
	'Smartcrawl_Admin'                                    => '/admin/admin.php',
	'Smartcrawl_Metabox'                                  => '/admin/metabox.php',
	'Smartcrawl_Settings_Admin'                           => '/admin/settings.php',
	'Smartcrawl_Taxonomy'                                 => '/admin/taxonomy.php',
	'Smartcrawl_Autolinks_UI'                             => '/admin/class-wds-autolinks-ui.php',
	'Smartcrawl_OnPage_UI'                                => '/admin/class-wds-onpage-ui.php',
	'Smartcrawl_SEO_Analysis_UI'                          => '/admin/class-wds-seo-analysis-ui.php',
	'Smartcrawl_Readability_Analysis_UI'                  => '/admin/class-wds-readability-analysis-ui.php',
	'Smartcrawl_Social_UI'                                => '/admin/class-wds-social-ui.php',
	// Classes in includes/admin/settings/.
	'Smartcrawl_Autolinks_Settings'                       => '/admin/settings/autolinks.php',
	'Smartcrawl_Settings_Dashboard'                       => '/admin/settings/dashboard.php',
	'Smartcrawl_Health_Settings'                          => '/admin/settings/health.php',
	'Smartcrawl_Onpage_Settings'                          => '/admin/settings/onpage.php',
	'Smartcrawl_Schema_Settings'                          => '/admin/settings/schema.php',
	'Smartcrawl_Settings_Settings'                        => '/admin/settings/settings.php',
	'Smartcrawl_Sitemap_Settings'                         => '/admin/settings/sitemap.php',
	'Smartcrawl_Social_Settings'                          => '/admin/settings/social.php',
	// Classes in includes/tools/.
	'Smartcrawl_Autolinks'                                => '/tools/autolinks.php',
	'Smartcrawl_OpenGraph_Printer'                        => '/tools/class-wds-opengraph-printer.php',
	'Smartcrawl_Pinterest_Printer'                        => '/tools/class-wds-pinterest-printer.php',
	'Smartcrawl_Social_Front'                             => '/tools/class-wds-social-front.php',
	'Smartcrawl_Twitter_Printer'                          => '/tools/class-wds-twitter-printer.php',
	'Smartcrawl_OnPage'                                   => '/tools/onpage.php',
	'Smartcrawl_Sitemaps_Dashboard_Widget'                => '/tools/sitemaps-dashboard-widget.php',
	'Smartcrawl_Xml_VideoSitemap'                         => '/tools/video-sitemaps.php',
	// Classes in includes/tools/seomoz/.
	'Smartcrawl_Controller_Moz_Cron'                      => '/tools/seomoz/class-wds-controller-moz-cron.php',
	'Smartcrawl_Moz_API'                                  => '/tools/seomoz/class-wds-moz-api.php',
	'Smartcrawl_Moz_Dashboard_Widget'                     => '/tools/seomoz/class-wds-moz-dashboard-widget.php',
	'Smartcrawl_Moz_Results_Renderer'                     => '/tools/seomoz/class-wds-moz-results-renderer.php',
	'Smartcrawl_Moz_Metabox'                              => '/tools/seomoz/class-wds-moz-metabox.php',
	// Classes in includes/.
	'Smartcrawl_Front'                                    => '/front.php',
	// Deprecated classes.
	'Smartcrawl_Canonical_Value_Helper'                   => '/deprecated/class-wds-canonical-value-helper.php',
	'Smartcrawl_Meta_Value_Helper'                        => '/deprecated/class-wds-meta-value-helper.php',
	'Smartcrawl_OpenGraph_Value_Helper'                   => '/deprecated/class-wds-opengraph-value-helper.php',
	'Smartcrawl_Replacement_Helper'                       => '/deprecated/class-wds-replacement-helper.php',
	'Smartcrawl_Robots_Value_Helper'                      => '/deprecated/class-wds-robots-value-helper.php',
	'Smartcrawl_Schema_Value_Helper'                      => '/deprecated/class-wds-schema-value-helper.php',
	'Smartcrawl_Social_Value_Helper'                      => '/deprecated/class-wds-social-value-helper.php',
	'Smartcrawl_Twitter_Value_Helper'                     => '/deprecated/class-wds-twitter-value-helper.php',
	'Smartcrawl_Type_Traverser'                           => '/deprecated/class-wds-type-traverser.php',
);
