# Base
echo "Setting color prompt"
echo "PS1='\[\e[1;36m\]\u \[\e[1;33m\]\w\$\[\e[0m\] '" >> /home/vagrant/.profile
echo "Creating deploy aliases"
echo "alias dp='cd /vagrant/;php deploy'" >> /home/vagrant/.profile
