# EC2SSH

## Installation

For now, it's not on Composer (private package), so you need to install from GitHub.

Edit your _global_ `composer.json` file (it's at `~/.composer/composer.json`) to add in this:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Radweb/ec2ssh"
    }
]
```

Now, run:

```
composer global require radweb/ec2ssh
```

## Configuration

Create yourself a pair of Access Keys: AWS Console > IAM > Users > *you* > Create Access Key

Now run:

```
ec2ssh config
```

Enter your keys when requested. For "Default Region" enter `eu-west-1` (_or use the up/down arrow keys to select it_).

## Usage

Just run:

```
ec2ssh
```

You'll be shown all your EC2 instances and prompted to pick one. Either enter the number or use the arrow keys to select a number.

An SSH session will then be started.
