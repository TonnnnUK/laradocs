const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();
    
    let pageUrl = 'https://nativephp.com/docs/1/getting-started/introduction';
    let fileName = '../json/nativePhp.json';
    
    await page.goto(pageUrl,  {
      waitUntil: "domcontentloaded",
    });


    // Get page data
    let navLinks = await page.evaluate((pageUrl) => {

      let links = [];

      // Get the Nav Items
      let nav = document.querySelector('nav');
      let navItems = nav.querySelectorAll('ul');
      let navItemsArray = Array.from(navItems);
      navItemsArray.shift();

      navItemsArray.forEach( (item) => {

        let topic = item.querySelector('a').innerText.trim();

            let subMenu = item.querySelectorAll('ul li');
            
            subMenu.forEach( (link) => {
                links.push({
                'topic': topic,
                'pageTitle': link.innerText,
                'url': link.querySelector('a').href,
                'link': link.querySelector('a').href,
                });
                
            });
       

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

            let pageTitle = document.querySelectorAll('main > div > div > div > div')[1].innerText.replace(/\n/g, "").trim();
            let pageLinks = [];

            let headingsList = document.querySelector('main > div > div > div > ul');
            let headingsListArray = Array.from(headingsList.children);

            let lastSubheading = '';
            headingsListArray.forEach( (heading) => {
                if( !heading.classList.contains("before:content-['##']") ){
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