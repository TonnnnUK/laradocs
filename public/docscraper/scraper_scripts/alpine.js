const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();

       // Launch Laravel Docs page
    await page.goto('https://alpinejs.dev/start-here',  {
        waitUntil: "domcontentloaded",
    });


    // Get page data
    let navLinks = await page.evaluate(() => {

      let links = [];

      // Get the Nav Items
      let nav = document.querySelector('aside ul');
      let navItems = Array.from(nav.children);

      navItems.forEach( (item) => {
        let topic = '';
        
        if(item.children[0].innerText == 'UI Components'){ return; }
        

        topic = item.children[0].innerText;
        if( item.children[0].nodeName == 'A'){
          links.push({
            topic: topic,
            pageTitle: item.querySelector('a').innerText,
            url: item.querySelector('a').href,
            link: item.querySelector('a').href,
          });
        }


        if( item.querySelector('ul')){
            let subMenu = item.querySelectorAll('ul li');

            subMenu.forEach( (link) => {
                
                links.push({
                  topic: topic,
                  pageTitle: link.querySelector('a').innerText,
                  url: link.querySelector('a').href,
                  link: link.querySelector('a').href,
                });
            
            });
        }
      });

      return links;

    });

    // Loop through the nav links
    for (const link of navLinks) {

      // Navigate to each link
      await page.goto(link.url, { waitUntil: "domcontentloaded" });
    
      let pageLinks = await page.evaluate((link) => {

            let pageTitle = document.querySelector("h1").innerText;
            let pageLinks = [];

            pageLinks.push({
                topic: pageTitle,
                page_title: pageTitle,
                section_title: pageTitle,
                link_title: '',
                url: link.url,
              });

              
            let headingsList = document.querySelectorAll("aside > ul")[1];
            let lastSectionHeading = '';
            
            let headingsArray = Array.from(headingsList.querySelectorAll('li'));

            headingsArray.forEach( (heading) => {
            
              if(!heading.classList.contains('ml-4')){
                lastSectionHeading = heading.querySelector('a').innerText; 
                pageLinks.push({
                    topic: link.topic,
                    page_title: pageTitle,
                    section_title: heading.querySelector('a').innerText,
                    link_title: '',
                    url: heading.querySelector('a').href,
                });
              } else {

                pageLinks.push({
                    topic: link.topic,
                    page_title: pageTitle,
                    section_title: lastSectionHeading,
                    link_title: heading.querySelector('a').innerText,
                    url: heading.querySelector('a').href,
                });
              }

            });

        return pageLinks;
      }, link);

      link.pageLinks = pageLinks;

      console.log("Scraped page URL:", page.url());
    }

    fs.writeFileSync('../json/alpine.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to alpine.json');

  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();