let pageTitle = document.querySelector("h1").innerText;
let topic = 'Getting Started';            
let pageLinks = [];

pageLinks.push({
	topic: topic,
	page_title: pageTitle,
	section_title: pageTitle,
	link_title: '',
	url: window.location.href
  });

  let pageElements = document.querySelector('.docs-main').children;
  let pageElementsArray = Array.from(pageElements);
  pageElementsArray.forEach( (element) => {

	
    if( element.nodeName == 'H2' || element.nodeName == 'H3'){

        pageLinks.push({
            topic: topic,
            page_title: pageTitle,
            section_title: element.innerText.replace(/\n/g, '').replace('#',''),
            link_title: '',
            url: element.querySelector('a').href,
        });
    }

  });

let addLinks = null;
if(localStorage.getItem('pest-links')){
	let cached = JSON.parse(localStorage.getItem('pest-links'));
	addLinks = cached.concat(pageLinks);
} else {
	addLinks = pageLinks;
}

localStorage.setItem('pest-links', JSON.stringify(addLinks));
