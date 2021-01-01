[![GPLv3 license](https://img.shields.io/badge/License-GPLv3-blue.svg)](http://perso.crans.org/besson/LICENSE.html)

# Wiki Block Plugin for Moodle
This project is devoted to creating a block plugin for Moodle that enables businesses or organizations to quickly set up an internal wiki of their digital tools and sources of information. This can especially be helpful in the broader context of setting up an effective onboarding strategy.

## Development Setup
### Docker :whale:
We've developed a Docker image to make the development setup as simple as possible. All you need to do is to [install Docker](https://www.docker.com/products/docker-desktop) and follow the steps below.
#### First Time Installation
When setting up your environment for the first time use the following steps:
1. Clone this repository: ``git clone https://github.com/wwu-ps-digital-onboarding/moodle-block_wiki.git``
2. Navigate to the root directory of the repository: ``cd moodle-block_wiki``
3. ``docker-compose up -d``
4. ``docker exec moodle-block_wiki_website php var/www/html/moodle/admin/cli/install_database.php --agree-license --adminpass=pw``
5. Open your webbrowser and navigate to ``localhost:8100/moodle``
6. Login with the following credentials:
    - username: admin
    - password: pw
7. Ready. Happy coding!

:warning: If you want to instantly see your newly created strings in the language files while developing, navigate to *'Site Administration'* -> *'Language settings'*. Then disable *'Cache all language strings'* and click *'Save changes'*.

#### Starting and Stopping
When finished with coding simply stop your Docker containers by doing the following:
1. If not already there, navigate to your local repository: ``cd path/to/repository``
2. Shut down your containers: ``docker-compose stop``
3. Done!

To start your containers proceed as follows:
1. If not already there, navigate to your local repository: ``cd path/to/repository``
2. Start your containers: ``docker-compose up -d``
3. Done!

:warning: If you don't stop your containers they will automatically start again as soon as you turn on your computer again. However leaving them running might take up unnecessary CPU, so it is recommended to stop them once you are done developing.

#### Updating
To reflect the latest changes, you don't need to go through the whole installation process again. Simply follow these steps:
1. If not already there, navigate to your local repository: ``cd path/to/repository``
2. Get the latest version: ``git pull``
3. Ready!

### Manually
You can of course also choose to set up your development environment manually. Once you have got a running Moodle website copy this repository into the ``blocks`` directory of your Moodle installation. However, while we already included several testing tools such as 'Code - Checker' in the Docker Development Setup you will have to install those tools on your own when setting up things manually.

## Contributing
### As a Contributor
As a contributor to contribute to the project, please follow the following steps:
1. Complete the [Development Setup](#development-setup)
2. If not already there, navigate to your local repository: ``cd path/to/repository``
3. Choose or create an [issue](https://github.com/wwu-ps-digital-onboarding/moodle-block_wiki/issues)
4. Create a branch for that issue: ``git checkout -b <branch-name>``
5. Add your code
6. ``git add .``
7. ``git commit -m "<your-message>"``
8. ``git push --set-upstream origin <branch-name>``
9. Create a pull request
10. Done!

### Code - Quality
To ensure high quality code, we've set up a list of quality measurements.
#### Coding Style
Please make sure that your code conforms to the [Moodle Coding Style](https://docs.moodle.org/dev/Coding_style). In our Docker Development Setup we included the Moodle Plugin 'Code-Checker'. To check whether your code complies with the Moodle Coding Style login to Moodle and navigate to Site Administration -> Development -> Code checker. Under 'Path(s) to check' enter ``blocks/wiki``. Then click on 'Check code'.
#### Unit - Tests
If your code is suitable to be tested with unit - tests please do so. Details on how to write unit - tests for Moodle Plugins can be found [here](https://docs.moodle.org/dev/PHPUnit). Please make sure to execute your written tests locally before committing your code.
#### Acceptance - Tests
Every new feature should come with at least one corresponding acceptance - test. Details on how to write acceptance - tests for Moodle Plugins can be found [here](https://docs.moodle.org/dev/Acceptance_testing). Please make sure to execute your written tests locally before committing your code.

## License
Code is under the GNU GENERAL PUBLIC LICENSE v3.
