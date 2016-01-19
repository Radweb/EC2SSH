# EC2SSH

![](https://i.imgur.com/JL5uWEd.jpg)

Note the data displayed is probably specific to our systems, may work weirdly for others.

## Installation

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
