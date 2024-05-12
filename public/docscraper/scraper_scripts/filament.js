const puppeteer = require('puppeteer');
const fs = require('fs');

async function run(){
  let browser;
  
  try {

    browser = await puppeteer.launch({headless: true});
    let page = await browser.newPage();
  
    // Launch Laravel Docs page
    await page.goto('https://filamentphp.com/docs/3.x/panels/installation',  {
      waitUntil: "domcontentloaded",
    });


    // Get page data
    let packageLinks = await page.evaluate(() => {

      let packageLinks = [];

      // Get the Nav Items
      let packageItems = document.querySelectorAll('.sticky > nav > ul');
      let packageItemsArray = Array.from(packageItems[0].children);

      packageItemsArray.forEach( (item) => {

        packageLinks.push({
            'topic': item.querySelector('a').innerText.replace(/\n/g, "").trim(),
            'pageTitle': item.querySelector('a').innerText.replace(/\n/g, "").trim(),
            'url': item.querySelector('a').href,
            'link': item.querySelector('a').href,
        });
         
      });

      return packageLinks;

    });


    // Loop through the nav links
    for (const packageLink of packageLinks) {

        // Navigate to each link
        await page.goto(packageLink.url, { waitUntil: "domcontentloaded" });

        let navLinks = await page.evaluate((packageLink) => {

            let links = [];

            // Get the Nav Items
            let navItems = document.querySelectorAll('.sticky > nav > ul');
            let navItemsArray = Array.from(navItems[1].children);

            navItemsArray.forEach( (item) => {

                links.push({
                    'topic': packageLink.topic,
                    'pageTitle': item.querySelector('a').innerText.replace(/\n/g, "").trim(),
                    'url': item.querySelector('a').href,
                    'link': item.querySelector('a').href,
                });


                let subMenu = item.querySelectorAll('ul li');

                subMenu.forEach( (sublink) => {
                    links.push({
                    'topic': packageLink.topic,
                    'pageTitle':  item.querySelector('a').innerText.replace(/\n/g, "").trim()+' - '+sublink.querySelector('a').innerText.replace(/\n/g, "").trim(),
                    'url': sublink.querySelector('a').href,
                    'link': sublink.querySelector('a').href,
                    });
                });
            });

            return links;

        }, packageLink);
    

        // Loop through the nav links
        for (const link of navLinks) {

            // Navigate to each link
            await page.goto(link.url, { waitUntil: "domcontentloaded" });
            
            // Here you can do whatever scraping you need for each page
            // For now, let's just log the page URL

            let pageLinks = await page.evaluate((link) => {
                let pageTitle = document.querySelector("h1").innerText.replace(/\n/g, "").trim();
                let pageLinks = [];

                let headingsList = document.querySelector('[aria-labelledby="on-this-page-title"] ol');
                if( headingsList){

                    let headingsListItems = Array.from(headingsList.children);

                    headingsListItems.forEach( (heading) => {

                        pageLinks.push({
                            topic: link.topic,
                            page_title: pageTitle,
                            section_title: heading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                            link_title: '',
                            url: heading.querySelector('a').href,
                        })

                        let subheadings = heading.querySelectorAll('ol > li');
                        subheadings.forEach( (subheading) => {
                            pageLinks.push({
                                topic: link.topic,
                                page_title: pageTitle,
                                section_title: heading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                                link_title: subheading.querySelector('a').innerText.replace(/\n/g, "").trim(),
                                url: heading.querySelector('a').href,
                            });
                        })

                        
                    })
                }

                return pageLinks;
            }, link);
            
            link.pageLinks = pageLinks;
            console.log("Scraped page URL:", page.url());
        }

        packageLink.pageLinks = navLinks;

    }


    let finalLinks = [];

    packageLinks.forEach( (package) => {
        finalLinks.push( package.pageLinks );
    } )


    fs.writeFileSync('../json/filament.json', JSON.stringify(finalLinks, null, 2));
    console.log('Data has been written to filament.json');

  } catch (e) {
    console.log('run failed', e);
  } finally {
    await browser.close();
  }

};

run();