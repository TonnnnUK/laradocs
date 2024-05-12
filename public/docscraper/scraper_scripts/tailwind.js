const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({
        headless: true,
    });

    let page = await browser.newPage();

       // Launch Laravel Docs page
    await page.goto('https://tailwindcss.com/docs/installation',  {
        waitUntil: "domcontentloaded",
    });


    // Get page data
    let navLinks = await page.evaluate(() => {

        let links = [];

        // Get the Nav Items
        let nav = document.querySelector('#nav ul');
        let navItems = Array.from(nav.children);

        navItems.forEach( (item) => {
            if(item.classList.contains('mt-12')){  
            
                let topic = item.querySelector('h5').innerText;
        
                if(topic == 'Official Plugins'){return}

                if( item.querySelector('ul')){
                    let subMenu = item.querySelectorAll('ul li');

                    
                    subMenu.forEach( (link) => {
                      if(link.querySelector('a').innerText == 'Installation'){return;}
                        
                        links.push({
                            topic: topic,
                            pageTitle: link.querySelector('a').innerText,
                            url: link.querySelector('a').href,
                            link: link.querySelector('a').href,
                        });
                    
                    });
                }
            }
        });

      return links;

    });

    // Loop through the nav links
    for (const link of navLinks) {

      // Navigate to each link
      await page.goto(link.url, { waitUntil: "domcontentloaded" });
      await new Promise(resolve => setTimeout(resolve, 250));

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

            let pageContent = document.querySelector("#content-wrapper");
            let lastSectionHeading = '';

            pageContent.childNodes.forEach(node => {
              if(node.tagName === 'H2'){
                
                lastSectionHeading = node.querySelector('a').innerText.replace(/\n/g, ''); 
                pageLinks.push({
                    topic: link.topic,
                    page_title: pageTitle,
                    section_title: node.querySelector('a').innerText.replace(/\n/g, ''),
                    link_title: '',
                    url: link.url+node.querySelector('a').href,
                });
              } 

              if( node.tagName === 'H3'){

                pageLinks.push({
                    topic: link.topic,
                    page_title: pageTitle,
                    section_title: lastSectionHeading,
                    link_title: node.querySelector('a').innerText,
                    url: link.url+node.querySelector('a').href,
                });
              }

            });

        return pageLinks;
      }, link);

      link.pageLinks = pageLinks;

      console.log("Scraped page URL:", page.url());
    }

    fs.writeFileSync('../json/tailwind.json', JSON.stringify(navLinks, null, 2));
    console.log('Data has been written to tailwind.json');

  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();