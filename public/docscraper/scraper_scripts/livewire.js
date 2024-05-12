const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();

       // Launch Laravel Docs page
    await page.goto('https://livewire.laravel.com/docs/quickstart',  {
        waitUntil: "domcontentloaded",
    });
  

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
      let nav = document.querySelector('[x-persist="docs-sidebar"] ul');

      let navItems = Array.from(nav.children);

      navItems.forEach( (item) => {

        let topic = window.toTitleCase(item.querySelector('h5').innerText);
        let subMenu = item.querySelectorAll('ul li');

        subMenu.forEach( (link) => {
            
            links.push({
              topic: topic,
              pageTitle: link.querySelector('a').innerText,
              url: link.querySelector('a').href,
              link: link.querySelector('a').href,
            });
           
        });
      });

      return links;

    });

    // Loop through the nav links
    for (const link of navLinks) {

      // Navigate to each link
      await page.goto(link.url, { waitUntil: "domcontentloaded" });
      
      // Add a delay of 1 second
      await new Promise(resolve => setTimeout(resolve, 15000));

      console.log("Scraped page URL:", page.url());
    
      let pageLinks = await page.evaluate((link) => {

            let pageTitle = document.querySelector("h1").innerText;
            let pageLinks = [];

            pageLinks.push({
                topic: pageTitle,
                pageTitle: pageTitle,
                sectionTitle: pageTitle,
                link_title: '',
                url: 'test',
              });

              
            let headingsList = document.querySelectorAll("ul.table-of-contents > li");

            headingsList.forEach( (heading) => {
            
                pageLinks.push({
                    topic: link.topic,
                    page_title: pageTitle,
                    section_title: heading.querySelector('a').innerText,
                    link_title: '',
                    url: heading.querySelector('a').href,
                });

                if( heading.querySelector('ul') ){

                    let subheadings = heading.querySelectorAll('ul li');
                
                    subheadings.forEach((subheading)=>{
                        pageLinks.push({
                            topic: link.topic,
                            page_title: pageTitle,
                            section_title: heading.querySelector('a').innerText,
                            link_title: subheading.querySelector('a').innerText,
                            url: subheading.querySelector('a').href,
                        });
                    });
                }

            });

        return pageLinks;
      }, link);

      link.pageLinks = pageLinks;

    }

    fs.writeFileSync('../json/livewire.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to output.json');

  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();