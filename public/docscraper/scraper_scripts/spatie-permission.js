
const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();
  
    // Launch Laravel Docs page
    await page.goto('https://spatie.be/docs/laravel-permission/v6/introduction',  {
      waitUntil: "domcontentloaded",
    });


    // Get page data
    let navLinks = await page.evaluate(() => {

      let links = [];

      // Get the Nav Items
      let navItems = document.querySelector('nav ol'); 
      let navItemsArray = Array.from(navItems.children);
      navItemsArray.shift();
      
      let topic = '';
      navItemsArray.forEach( (item) => {
        if( item.nodeName == 'H2'){
            topic = item.innerText;
        }

        if ( item.nodeName == 'UL'){

            let subMenu = item.querySelectorAll('li');
    
            subMenu.forEach( (link) => {
                links.push({
                  'topic': topic,
                  'pageTitle': link.querySelector('a').innerText,
                  'url': link.querySelector('a').href,
                  'link': link.querySelector('a').href,
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
      await new Promise(resolve => setTimeout(resolve, 250));      
      // Here you can do whatever scraping you need for each page
      // For now, let's just log the page URL

      let pageLinks = await page.evaluate((link) => {


        let pageTitle = document.querySelector("h1").innerText;
        let pageLinks = [];
        let headingsList = document.querySelector('aside ul');
        
        if(!headingsList){ 

          pageLinks.push({
            topic: link.topic,
            page_title: pageTitle,
            section_title: pageTitle,
            link_title: '',
            url: window.location.href,
          });

        

        } else {

          let headingsListArray = Array.from(headingsList.children);
  
          headingsListArray.forEach( (heading) => {
              
              pageLinks.push({
                  topic: link.topic,
                  page_title: pageTitle,
                  section_title: heading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                  link_title: '',
                  url: heading.querySelector('a').href,
              });
  
          })
        }
        


        return pageLinks;
      }, link);

      
      console.log("Scraping page URL:", page.url());

      link.pageLinks = pageLinks;
      
    }

    fs.writeFileSync('../json/spatie-permission.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to spatie-permission.json');


  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();