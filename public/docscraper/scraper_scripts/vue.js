const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();
  

    // let pageUrl = 'https://vuejs.org/guide/introduction.html'; // Vue Guide page
    let pageUrl = 'https://vuejs.org/api/application.html'; // Vue API page

    
    await page.goto(pageUrl,  {
      waitUntil: "domcontentloaded",
    });


    // Get page data
    let navLinks = await page.evaluate(() => {

      let links = [];

      // Get the Nav Items
      let navItems = document.querySelectorAll('nav#VPSidebarNav > .group');
      let navItemsArray = Array.from(navItems);
      
      navItemsArray.forEach( (item) => {
        let topic = item.querySelector('section > .title').innerText;
        let subMenu = item.querySelectorAll('section > a');

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

        let pageTitle = document.querySelector("main h1").innerText.replace(/\n/g, "").trim();
        let pageLinks = [];

        let headingsList = document.querySelectorAll(".VPContentDocOutline nav > ul > li");
        let headingsListArray = Array.from(headingsList);

        headingsListArray.forEach( (heading) => {
          
            pageLinks.push({
                topic: link.topic,
                page_title: pageTitle,
                section_title: heading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                link_title: '',
                url: heading.querySelector('a').href,
            });


          if( heading.querySelector('ul') ){
            let subheadings = heading.querySelectorAll('ul li');
            let subheadingsArray = Array.from(subheadings);

            subheadingsArray.forEach( (subheading) => {
              pageLinks.push({
                topic: link.topic,
                page_title: pageTitle,
                section_title: heading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                link_title: subheading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                url: heading.querySelector('a').href,
              });
            })

          }
        });


        return pageLinks;
      }, link);

      
      console.log("Scraping page URL:", page.url());

      link.pageLinks = pageLinks;
      
    }

    // fs.writeFileSync('../json/vue.json', JSON.stringify(navLinks, null, 2));
    fs.writeFileSync('../json/vue-api.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to vue.json');


  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();