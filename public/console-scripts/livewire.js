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

let headingsList = document.querySelectorAll("ul.table-of-contents > li");

headingsList.forEach( (heading) => {

	pageLinks.push({
		topic: topic,
		page_title: pageTitle,
		section_title: heading.querySelector('a').innerText,
		link_title: '',
		url: heading.querySelector('a').href,
	});

	if( heading.querySelector('ul') ){

		let subheadings = heading.querySelectorAll('ul li');
	
		subheadings.forEach((subheading)=>{
			pageLinks.push({
				topic: topic,
				page_title: pageTitle,
				section_title: heading.querySelector('a').innerText,
				link_title: subheading.querySelector('a').innerText,
				url: subheading.querySelector('a').href,
			});
		});
	}

});

let addLinks = null;
if(localStorage.getItem('livewire-links')){
	let cached = JSON.parse(localStorage.getItem('livewire-links'));
	addLinks = cached.concat(pageLinks);
} else {
	addLinks = pageLinks;
}

localStorage.setItem('livewire-links', JSON.stringify(addLinks));
