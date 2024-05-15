const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();
  

    // let pageUrl = 'https://react.dev/learn/installation'; // React Learn page
    // let fileName = '../json/react-learn.json';
    
    let pageUrl = 'https://react.dev/reference/react'; // React Reference page
    let fileName = '../json/react-reference.json';

    
    await page.goto(pageUrl,  {
      waitUntil: "domcontentloaded",
    });


    // Get page data
    let navLinks = await page.evaluate((pageUrl) => {

      let links = [];

      // Get the Nav Items
      let navItems = document.querySelectorAll('aside > nav > ul')[0].children;
      let navItemsArray = Array.from(navItems);

      if( pageUrl == 'https://react.dev/learn/installation') {
        navItemsArray.shift();
        navItemsArray.shift();
      }
      
      navItemsArray.forEach( (item) => {

        if( item.nodeName != 'LI' || item.role ) { return; }

        let topic = item.querySelector('a').innerText.trim();

        if(item.querySelector('a')){

            links.push({
                'topic': topic,
                'pageTitle': topic,
                'url': item.querySelector('a').href,
                'link': item.querySelector('a').href,
              });
    
    
              if(item.querySelector('div')){
                  let subMenu = item.querySelectorAll('div ul li');
          
                  subMenu.forEach( (link) => {
                      links.push({
                        'topic': topic,
                        'pageTitle': link.innerText,
                        'url': link.querySelector('a').href,
                        'link': link.querySelector('a').href,
                      });
                     
                  });
              }
        }

      });

      return links;

    }, pageUrl);

    // Loop through the nav links
    for (const link of navLinks) {

      // Navigate to each link
      await page.goto(link.url, { waitUntil: "domcontentloaded" });
      await new Promise(resolve => setTimeout(resolve, 250));      
      // Here you can do whatever scraping you need for each page
      // For now, let's just log the page URL

        let pageLinks = await page.evaluate((link) => {

            let pageTitle = document.querySelector("main h1").innerText.replace(/\n/g, "").trim();
            let pageLinks = [];

            let headingsList = document.querySelectorAll('nav[role="navigation"]')[1].querySelectorAll('li');
            let headingsListArray = Array.from(headingsList);

            let lastSubheading = '';
            headingsListArray.forEach( (heading) => {
                if( !heading.classList.contains('ps-4') ){
                    lastSubheading = heading.querySelector('a').innerText.replace(/\n/g, "").trim();
        
                    pageLinks.push({
                        topic: link.topic,
                        page_title: pageTitle,
                        section_title: heading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                        link_title: '',
                        url: heading.querySelector('a').href,
                    });
                } else {
                    pageLinks.push({
                        topic: link.topic,
                        page_title: pageTitle,
                        section_title: lastSubheading,
                        link_title: heading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                        url: heading.querySelector('a').href,
                    });

                }
            });


            return pageLinks;
        }, link);

      
      console.log("Scraping page URL:", page.url());

      link.pageLinks = pageLinks;
      
    }

    
    fs.writeFileSync(fileName, JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to '+fileName);


  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();