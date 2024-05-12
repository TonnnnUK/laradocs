const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();

       // Launch Laravel Docs page
    await page.goto('https://inertiajs.com/',  {
        waitUntil: "domcontentloaded",
    });
  

    // Get page data
    let navLinks = await page.evaluate(() => {

        window.toTitleCase = function (str) {
            // Split the string into words
            return str.replace(/\w\S*/g, function(txt) {
                // Capitalize the first letter of each word
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

      let links = [];

      // Get the Nav Items
      let nav = document.querySelector('nav').children;

      let navArray = Array.from(nav);
      navArray.shift();
      navArray.shift();

      let topic = ''; 

      navArray.forEach( (item) => {

        if( item.nodeName == 'DIV' ){
            topic = window.toTitleCase(item.innerText);
            return;
        }

        if( item.nodeName == 'UL'){

            let subMenu = item.querySelectorAll('li');
    
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

      console.log('nav links', links);
      return links;

    });



    // // Loop through the nav links
    for (const link of navLinks) {

      // Navigate to each link
      await page.goto(link.url, { waitUntil: "domcontentloaded" });
      
      console.log("Scraping page URL:", page.url());
    
      let pageLinks = await page.evaluate((link) => {

            let pageTitle = document.querySelector("h1").innerText;
            let pageLinks = [];

            pageLinks.push({
                topic: link.topic,
                page_title: pageTitle,
                section_title: pageTitle,
                link_title: '',
                url: link.url,
              });

              
            let headingsList = document.querySelectorAll(".sticky ul > li");

            headingsList.forEach( (heading) => {
            
                pageLinks.push({
                    topic: link.topic,
                    page_title: pageTitle,
                    section_title: heading.querySelector('a').innerText,
                    link_title: '',
                    url: heading.querySelector('a').href,
                });

            });

        return pageLinks;
      }, link);

      link.pageLinks = pageLinks;

    }

    fs.writeFileSync('../json/inertia.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to inertia.json');

  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();