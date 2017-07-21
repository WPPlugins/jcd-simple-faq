jQuery(document).ready(function() { 
    jQuery("#faq-accordion:nth-child(1n)").accordion({
        collapsible: true,
        active: false,
        heightStyle: "content"
    });
    jQuery('#faq-accordion:nth-child(1n) .accordion-toggle').click(function () {
        jQuery(this).find("i").toggleClass( "fa-rotate-90" )
    });
});