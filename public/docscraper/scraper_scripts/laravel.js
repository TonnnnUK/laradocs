const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();
  
    // Launch Laravel Docs page
    await page.goto('https://laravel.com/docs/',  {
      waitUntil: "domcontentloaded",
    });


    // Get page data
    let navLinks = await page.evaluate(() => {

      let links = [];

      // Get the Nav Items
      let navItems = document.querySelectorAll('#indexed-nav .docs_sidebar > ul > li');
      let navItemsArray = Array.from(navItems);
      // Remove the first and last elements
      navItemsArray = navItemsArray.slice(1, -1);
      navItemsArray.forEach( (item) => {
        let subMenu = item.querySelectorAll('ul li');

        subMenu.forEach( (link) => {
          if( link.querySelector('a').innerText != 'Jetstream' ){
            links.push({
              'topic': item.querySelector('h2').innerText,
              'pageTitle': link.querySelector('a').innerText,
              'url': link.querySelector('a').href,
              'link': link.querySelector('a').href,
            });
          } 
        });
      });

      //  console.log('tha nav links', links);
      return links;

    });

    // Loop through the nav links
    for (const link of navLinks) {

      // Navigate to each link
      await page.goto(link.url, { waitUntil: "domcontentloaded" });
      
      // Here you can do whatever scraping you need for each page
      // For now, let's just log the page URL

      let pageLinks = await page.evaluate((link) => {
        let pageTitle = document.querySelector("#main-content h1").innerText;
        let pageLinks = [];

        let headingsList = document.querySelectorAll("#main-content > ul > li");

        headingsList.forEach( (heading) => {

          if( !heading.querySelector('a') ){
            return;
          }
          
          let headingInfo = {
            topic: link.topic,
            page_title: pageTitle,
            section_title: heading.querySelector('a').innerText,
            link_title: '',
            url: heading.querySelector('a').href,
          };

          if( heading.querySelector('ul') ){
            pageLinks.push(headingInfo);

            let subheadings = heading.querySelectorAll('ul > li');
            subheadings.forEach( (subheading) => {
              pageLinks.push({
                topic: link.topic,
                page_title: pageTitle,
                section_title: heading.querySelector('a').innerText,
                link_title: subheading.querySelector('a').innerText,
                url: subheading.querySelector('a').href,
              });
            })

          } else {
            pageLinks.push(headingInfo);
          }

        })

        return pageLinks;
      }, link);

      console.log("Scraping page URL:", page.url());
      link.pageLinks = pageLinks;      

    }

    // datestamp in format YYYY-MM-DD-HH-MM-SS
    let date = new Date().toISOString().replace(/T/, ' ').replace(/\..+/, '').replace(/:/g, '-');
    fs.writeFileSync('../json/laravel-'+date+'.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to laravel.json');


  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();