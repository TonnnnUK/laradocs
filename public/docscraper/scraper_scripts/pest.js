
const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: false});
    let page = await browser.newPage();
  
    // Launch Laravel Docs page
    await page.goto("https://pestphp.com/docs/installation", { waitUntil: "domcontentloaded", timeout: 0 });
    await new Promise(resolve => setTimeout(resolve, 250));

     // Inject the toTitleCase function into the browser context
     await page.evaluate(() => {
        window.toTitleCase = function (str) {
          return str.replace(/\b\w/g, char => char.toUpperCase());
        }
    });


    // Get page data
    let navLinks = await page.evaluate(() => {

      let links = [];

      // Get the Nav Items
      let nav = document.querySelector('.docs-nav > ul');
      let navItems = nav.children;
      let navItemsArray = Array.from(navItems);
      navItemsArray.shift();

      navItemsArray.forEach( (item) => {
        let topic = window.toTitleCase(item.querySelector('h2').innerText);
        let subMenu = item.querySelectorAll('ul li');

        subMenu.forEach( (link) => {
            links.push({
              'topic': topic,
              'pageTitle': link.innerText,
              'url': link.href,
              'link': link.href,
            });
           
        });
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

        let pageElements = document.querySelector('.docs-main').children;
        let pageElementsArray = Array.from(pageElements);
        pageElementsArray.forEach( (element) => {
          
            if( element.nodeName == 'H2'){

                pageLinks.push({
                    topic: link.topic,
                    page_title: pageTitle,
                    section_title: element.innerText.replace(/\n/g, '').replace('#',''),
                    link_title: '',
                    url: element.querySelector('a').href,
                });
            }

        })


        return pageLinks;
      }, link);

      
      console.log("Scraping page URL:", page.url());

      link.pageLinks = pageLinks;
      
    }

    fs.writeFileSync('../json/pest.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to pest.json');


  } catch (e) {
    console.log('run failed', e);
  } finally {
    // await browser.close();
  }

};

run();